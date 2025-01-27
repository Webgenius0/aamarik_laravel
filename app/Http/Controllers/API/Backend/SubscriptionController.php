<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\userSubscriptionsResource;
use App\Models\Masjid;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Customer;
use Stripe\Invoice;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class SubscriptionController extends Controller
{
    use apiresponse;
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    /**
     * Get current user subscription
     */
    public function getMySubscriptions()
    {
        //get current auth
        $user = auth()->user();

        // Check if the user has a Stripe customer ID
        if (!$user || !$user->stripe_customer_id) {
            return $this->sendError('Stripe customer ID not found for the user.');
        }


        try {
            // Retrieve all subscriptions for the Stripe customer ID
            $subscriptions = \Stripe\Subscription::all([
                'customer' => $user->stripe_customer_id,
                'status' => 'active', // Only fetch active subscriptions
            ]);

            // Map subscriptions into a structured response
            $response = collect($subscriptions->data)->map(function ($subscription) {
                try {
                    $productId = json_decode(json_encode($subscription), true)['plan']['product'];



                    // Try to retrieve the product name
                    $product =  \Stripe\Product::retrieve($productId);

                    // Fetch the latest invoice for the subscription
                    $invoice = Invoice::retrieve($subscription->latest_invoice);

                    //get invoice charge
                    $chargeID = $invoice->charge; //get invoice charge id


                    // Initialize card details
                    $cardDetails = [
                        'brand' => null,
                        'last4' => null,
                    ];

                    if ($chargeID) {
                        //get charge details with charge id
                        $charge =  \stripe\Charge::retrieve($chargeID);

                        if ($charge->payment_method_details->type === 'card') {
                            $cardDetails['brand'] =  $charge->payment_method_details->card->brand;
                            $cardDetails['last4'] =  $charge->payment_method_details->card->last4;
                        }
                    }

                    // Attach card details to the subscription object
                    $subscription->card = $cardDetails;

                } catch (\Exception $e) {
                    Log::warning('Failed to retrieve product.', [
                        'product_id' => $subscription->plan->product,
                        'error' => $e->getMessage(),
                    ]);
                }
                // Return a custom response
                return  new userSubscriptionsResource($subscription);
            });

            // Return the response
            return $this->sendResponse($response,'Subscriptions retrieved successfully.');
        } catch (\Exception $e) {
            // Handle any errors and return an error response
            Log::error('Failed to retrieve subscriptions.', ['error' => $e->getMessage()]);

            return $this->sendError('Failed to retrieve subscriptions.', [],422);
        }
    }

    /**
     * Delete Stripe current user subscription with subscription
     */
    public function deleteSubscription($id)
    {
        //get current auth
        $user = auth()->user();

        // Check if the user has a Stripe customer ID
        if (!$user || !$user->stripe_customer_id) {
            return $this->sendError('Stripe customer ID not found for the user.');
        }

        try {
            // Retrieve the subscription
            $subscription = \Stripe\Subscription::retrieve($id);

            // Check if the subscription belongs to the user and is active
            if (!empty($subscription) && $subscription->customer == $user->stripe_customer_id && $subscription->status == 'active') {
                // Cancel the active subscription
                $subscription->cancel();

                return $this->sendResponse([], 'Subscription has been successfully canceled.');
            } else {
                return $this->sendError('Subscription not found or is not active.');
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle Stripe API error
            return $this->sendError('Failed to delete subscription: ' . $e->getMessage());

        }

    }

}
