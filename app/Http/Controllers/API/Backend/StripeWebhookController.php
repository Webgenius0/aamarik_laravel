<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        Log::info('Stripe Webhook Received', ['payload' => $payload]);
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            Log::info('Stripe Event Type:', ['type' => $event->type]);
            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;

                    $uuid = $paymentIntent->metadata->uuid;

                    Log::info('Payment Intent Succeeded', ['uuid' => $uuid]);

                    if ($uuid) {
                        Order::where('uuid', $uuid)->update(['status' => 'paid']);
                        Log::info('Order status updated to paid', ['uuid' => $uuid]);
                    } else {
                        Log::error('Order UUID not found in metadata', ['payment_intent' => $paymentIntent]);
                    }
                    return response()->json([
                        'message' => 'order payment success.'
                    ]);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;

                    $uuid = $paymentIntent->metadata->uuid; //get metadata uuid
                    Log::info('Payment Intent Failed', ['uuid' => $uuid]);

                    if ($uuid) {
                        Order::where('uuid', $uuid)->update(['status' => 'failed']);
                        Log::info('Order status updated to failed', ['uuid' => $uuid]);
                    } else {
                        Log::error('Order UUID not found in metadata', ['payment_intent' => $paymentIntent]);
                    }
                    return response()->json(['message' => "Order #{$uuid} payment failed."], 404);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook signature verification failed: ' . $e->getMessage()], 400);
        }
    }
}
