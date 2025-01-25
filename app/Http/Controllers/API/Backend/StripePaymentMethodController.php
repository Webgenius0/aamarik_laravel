<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\StripeCardResource;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\Subscription;

class StripePaymentMethodController extends Controller
{
    use apiresponse;
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
   /**
    * Add payment method to customer
    */
   public function addMethodToCustomer(Request $request)
   {
       // Validate incoming request
       $validator = Validator::make($request->all(), [
           'payment_method_id' => 'required|string',
       ]);
       // If validation fails, return error message
       if ($validator->fails()) {
           return $this->sendError('Validation error:'.$validator->errors()->first(),[], 422); // Change the HTTP code if needed
       }

       // Retrieve validated data
       $validatedData = $validator->validated();

       try {
           $paymentMethod = PaymentMethod::retrieve($validatedData["payment_method_id"]);

           // Check if the PaymentMethod is attached or reusable
           if ($paymentMethod->attached) {
               return  $this->sendError('This payment method is already attached to a customer or is not reusable',[], 422);
           }

           // Create a new customer if it doesn't exist
           $customer = $this->createCustomerIfNotExist();
           // Attach the payment method to the customer
           $paymentMethod->attach([
               'customer' => $customer->id,
           ]);
           Customer::update($customer->id, [
               "invoice_settings" => [
                   'default_payment_method' => $request->payment_method_id,
               ]
           ]);

           return $this->sendResponse([], 'Card added successfully');
       } catch (\Exception $e) {
           return $this->sendError($e->getMessage(), [], 422);
       }
   }



   /**
    * Create a new customer if it doesn't exist
    */
    private function createCustomerIfNotExist()
    {
        //get current user
        $user = auth()->user();
        //customer data
        $customerData = [
            'name' => $user->name,
        ];
        // Check if the user has an email
        if (!empty($user->email)) {
            $customerData['email'] = $user->email;
        }

        // Check if the user has a Stripe customer ID
        if (empty($user->stripe_customer_id)) {
            // If not, create a new Stripe customer
            $customer = Customer::create($customerData);
            $user->stripe_customer_id = $customer->id;
            $user->save();
        } else {
            // Retrieve the existing customer on Stripe
            $customer = Customer::retrieve($user->stripe_customer_id);
            if (!$customer) {
                $customer = Customer::create($customerData);
                $user->stripe_customer_id = $customer->id;
                $user->save();
            }
        }
        return $customer;
    }


    /**
     * Retrieve customer payment methods with auth user stripe customer ID
     */
    public function getCustomerPaymentMethods()
    {
        try {
            // Get the current user
            $user = auth()->user();

            // Check if the user has a Stripe customer ID
            if ($user->stripe_customer_id === null || empty($user->stripe_customer_id)) {
                return $this->sendError('No valid Stripe customer ID found. Please create a Stripe account to add payment methods.',(object)[],404);
            }

            // Retrieve the Stripe customer
            $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);

            // If customer retrieval fails
            if (!$customer || empty($customer->id)) {
                return $this->sendError('Customer not found in Stripe.',(object)[],404);

            }

            // Retrieve the customer's payment methods
            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customer->id,
                'type' => 'card',
            ]);

            // Check if no payment methods are found
            if (empty($paymentMethods->data)) {
                return $this->sendError('No payment methods found.',(object)[],404);
            }

            // Convert Stripe\Collection data into a Laravel Collection
            $paymentMethodsCollection = collect($paymentMethods->data);

            // Return the first payment method
            return $this->sendResponse(StripeCardResource::collection($paymentMethodsCollection), 'Payment methods retrieved successfully.');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle specific Stripe API errors
            return $this->sendError('Stripe API error: ' . $e->getMessage(), [], 500);
        } catch (\Exception $e) {
            // Handle general exceptions
            return $this->sendError( 'An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    /**
     * Remove payment method
     */
    public function removeCustomerPaymentMethod($paymentMethodID)
    {
        try {
            //get current user
            $user = auth()->user();

            // Check if the user has a Stripe customer ID
            if ($user->stripe_customer_id === null || empty($user->stripe_customer_id)) {
                return $this->sendError('No valid Stripe customer ID found. Please create a Stripe account to add payment methods.',(object)[],404);
            }

            // check if the user has a stripe customer id
            $customer = Customer::retrieve($user->stripe_customer_id);
            if (!$customer || empty($customer->id)) {
                return $this->sendError('Customer not found in Stripe.',(object)[],404);
            }

            // Retrieve the payment method by ID
            $paymentMethod = \Stripe\PaymentMethod::retrieve($paymentMethodID);
            
            // Check if the payment method exists
            if (!$paymentMethod || empty($paymentMethod->id)) {
                return $this->sendError('Payment method not found.', [], 404);
            }

            $this->deleteSubscriptionAndCustomer(); // delete customer and subscription
            $paymentMethod->detach(); // detach payment method

            return $this->sendResponse([], 'Payment method removed successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }


    // Delete Subscription and Customer
    private function deleteSubscriptionAndCustomer()
    {
        //get current user
        $user = auth()->user();
        // check if the user has a stripe customer id
        $customer = Customer::retrieve($user->stripe_customer_id);
        if (!$customer) {
            return $this->sendError('Customer not found in Stripe.',(object)[],404);
        }
        $subscriptions = Subscription::all([
            'customer' => $customer->id,
        ]);
        foreach ($subscriptions as $subscription) {
            $subscription->delete();
        }

        return ;
    }

}
