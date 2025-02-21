<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\order_item;
use App\Traits\apiresponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    use apiresponse;
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }


    // Handle Stripe Webhook
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload        = $request->getContent();
        $sigHeader      = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    if($paymentIntent->invoice){
                        $invoice = \Stripe\Invoice::retrieve($paymentIntent->invoice);
                        $subscription = \Stripe\Subscription::retrieve($invoice->subscription);
                        $uuid = $subscription?->metadata?->order_uuid;

                        if($uuid){
                            $this->handleSubscriptionOrder($uuid);
                        }
                    }else{
                        $uuid = $paymentIntent->metadata->order_uuid;
                    }


                    if ($uuid) {
                        //find order with uuid
                        $order = Order::where('uuid', $uuid)->first();

                        if ($order) {
                            $order->status = 'paid';
                            $order->pay_amount = $order->total_price;
                            $order->save();
                        }

                    } else {
                        Log::error('Order UUID not found in metadata', ['payment_intent' => $paymentIntent]);
                    }
                    return response()->json([
                        'message' => 'order payment success.',
                        'uuid' => $uuid
                    ]);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;

                    $uuid = $paymentIntent->metadata->order_uuid; //get metadata uuid
                    Log::info('Payment Intent Failed', ['uuid' => $uuid]);

                    if ($uuid) {
                        //find order with uuid
                        $order = Order::where('uuid', $uuid)->first();
                        if ($order) {
                            $order->status = 'failed';
                            $order->due_amount = $order->total_price;
                            $order->save();
                            Log::info('Order status updated to failed', ['uuid' => $uuid]);
                        }
                        Log::info('not found order ', ['uuid' => $uuid]);
                    } else {
                        Log::error('Order UUID not found in metadata');
                    }
                    return response()->json(['message' => "Order #{$uuid} payment failed."], 404);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook signature verification failed: ' . $e->getMessage()], 400);
        }
    }

    /**
     * handle Subscription Order
     */
    private function handleSubscriptionOrder($uuid)
    {
        $expiredDate = Carbon::now()->subDays(30);
        $order = Order::where('uuid', $uuid)->first();
        if ($order) {
            if ($order->created_at < $expiredDate ) {
                $order->status = 'paid';
                $order->pay_amount = $order->total_price;
                $order->save();
            }else{
                $this->manageSubscriptionOrder($uuid);
            }
        }else{
            Log::info('not found order ', ['uuid' => $uuid]);
        }
    }

    /**
     * manage Subscription Order
     */
    private function manageSubscriptionOrder($uuid)
    {
        // Start a transaction to ensure atomicity
        DB::beginTransaction();
        try {
            $Order = Order::with('orderItems')->where('uuid', $uuid)->first();
            if($Order){
                $OrderUserID   = $Order->user_id;
                $orderSubTotal = $Order->sub_total; //only calculate medicine total amount


                $shippingCharge = $orderSubTotal * 0.02;
                $royalMailTrackedPrice = $Order->royal_mail_tracked_price ?? 0;
                $tax = ($orderSubTotal + $shippingCharge + $royalMailTrackedPrice) * 0.10;

                $totalPrice = max($orderSubTotal + $shippingCharge + $tax + $royalMailTrackedPrice, 0.50); // Ensure it's at least $0.50


                //check or decrement medicine stock
                $orderItems = $Order->orderItems;

                foreach($orderItems as $orderItem){
                    $orderMedicineID = $orderItem->medicine_id; // medicine id
                    $medicineRecord = Medicine::with('details')->find($orderMedicineID);//find medicine
                    if($medicineRecord){
                        $details = $medicineRecord->details; // Access the related details model

                        if ($medicineRecord && $details->stock_quantity >= $orderItem->quantity) {
                            $details->decrement('stock_quantity', $orderItem->quantity);
                        }
                    }
                }


                //create new order base one subscription
                $newOrder = Order::create([
                    'uuid'                     => substr((string) Str::uuid(), 0, 8),
                    'user_id'                  => $OrderUserID,
                    'treatment_id'             => $Order->treatment_id ,
                    'coupon_id'                => null,
                    'tracked'                  => $royalMailTrackedPrice ? 1 : 0,
                    'royal_mail_tracked_price' => $royalMailTrackedPrice,
                    'sub_total'                => $orderSubTotal, // Subtotal
                    'discount'                 => 0,
                    'shipping_charge'          => $shippingCharge, // Shipping charge (2% of discounted subtotal)
                    'tax'                      => $tax, // Tax (10% of discounted subtotal + shipping charge + royal mail tracked price)
                    'total_price'              => $totalPrice, // Total amount
                    'pay_amount'               => $totalPrice,
                    'subscription'             => false,
                    'status'                   => 'paid',
                    'created_at'               => now(),
                ]);

                //create new order items
                foreach($orderItems as $orderItem){
                    $orderItem = new order_item([
                        'order_id'    => $newOrder->id,
                        'medicine_id' => $orderItem->medicine_id,
                        'quantity'    => $orderItem->quantity,
                        'unit_price'  => $orderItem->unit_price ?? 0,
                        'total_price' => $orderItem->total_price ?? 0,
                    ]);
                    $newOrder->orderItems()->save($orderItem);
                }
            }
            DB::commit();
            Log::info('create new order base one subscription');
        }catch (\Exception $exception){
            DB::rollBack();
            Log::error('Failed to create new order base one subscription');
        }
    }
}
