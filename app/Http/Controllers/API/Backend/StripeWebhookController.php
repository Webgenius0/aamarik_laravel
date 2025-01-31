<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
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

                    return response($paymentIntent);
                    $uuid = $paymentIntent->metadata->uuid;
                    Order::where('uuid', $uuid)->update(['status'=>'paid']);
                    return response()->json([
                        'message' => 'order #'.$uuid.'id payment success.'
                    ]);
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;

                    $uuid = $paymentIntent->metadata->uuid; //get metadata uuid
                    Order::where('uuid', $uuid)->update(['status'=>'failed']);
                    return response()->json([
                        'message' =>'order #'.$uuid.'id payment fail.'
                    ]);
                    break;
            }
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Webhook signature verification failed: ' . $e->getMessage()], 400);
        }
    }
}
