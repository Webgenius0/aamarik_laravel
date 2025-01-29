<?php

namespace App\Http\Controllers\API\Frontend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCheckoutRequest;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\BilingAddress;
use App\Models\Coupon;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\order_item;
use App\Notifications\OrderNotificationToUser;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Subscription;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class OrderManagement extends Controller
{
    use apiresponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create order
     */
    public  function  orderCheckout(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'treatment_id' => 'required|integer',
            'royal_maill_tracked_price' => 'nullable|numeric',
            'code' => 'nullable|string|exists:coupons,code', // coupons code
            'subscription' => 'required|boolean',
            'prescription' => 'nullable|nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'payment_method_id' => 'required|string',

            //amount
            'sub_total' => 'nullable|numeric',
            'discount'  => 'nullable|numeric',
            'total'     => 'nullable|numeric',

            // Medicines array
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|integer|exists:medicines,id', // assuming medicine_id exists in the medicines table
            'medicines.*.quantity' => 'required|integer|min:1',
            'medicines.*.unit_price' => 'nullable|numeric',
            'medicines.*.total_price' => 'nullable|numeric',

            // Assessments array
            'assessments' => 'required|array',
            'assessments.*.assessment_id' => 'required|integer|exists:assessments,id',
            'assessments.*.selected_option' => 'nullable|string|max:255',
            'assessments.*.result' => 'nullable|string|max:255',
            'assessments.*.notes' => 'nullable|string|max:255',

            // Billing address fields (nullable)
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:15',
            'city' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:20',
            'gp_number' => 'nullable|string|max:255',
            'gp_address' => 'nullable|string|max:255',
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation failed. Please check the provided details and try again.',$validator->errors()->toArray(), 422); // Change the HTTP code if needed
        }

        // Start a transaction to ensure atomicity
      DB::beginTransaction();

        try {
            // Retrieve validated data
            $validatedData = $validator->validated();

            //get current user
            $user = auth()->user();

            // Ensure the user has a Stripe customer ID
            if (empty($user->stripe_customer_id)) {
                return $this->sendError('No Stripe customer ID found. Please add a payment method and try again.',[]);
            }
            // Retrieve the customer from Stripe
            $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);

            // Check if the Stripe customer is invalid
            if (!$customer || empty($customer->id)) {
                return $this->sendError( 'Stripe customer not found. Please ensure your payment details are up-to-date.',[]);
            }



            // Handle coupon validation
            if (isset($validatedData['code'])) {
                $coupon = Coupon::where('code', $validatedData['code'])->first();

                // Check if the coupon is valid
                if ($coupon && !$coupon->isValid()) {
                    return $this->sendError('The coupon is invalid or expired.', [], 410);
                }
            }


            // Calculate the total price for the medicines and update stock quantity
            $totalPrice = 0;
            foreach ( $validatedData['medicines'] as $medicine) {
                // Decrement medicine stock_quantity
                $medicineRecord = Medicine::with('details')->find($medicine['medicine_id']);
                // Check if the medicine exists

                // Access the related details model
                $details = $medicineRecord->details;

                if ($medicineRecord) {
                    // Ensure stock is sufficient before decrementing
                    if ($details->stock_quantity < $medicine['quantity']) {
                        throw new \Exception("Low stock for medicine");
                    }

                    // Update stock quantity
                    $details->decrement('stock_quantity', $medicine['quantity']);
                } else {
                    throw new \Exception("Medicine " . $medicineRecord->title . " not found.");
                }

                $totalPrice += $medicine['total_price'];
            }

            //sub total price
            $sub_total  = $totalPrice + ($validatedData['royal_maill_tracked_price'] ?? 0);


            // Apply the discount
            $discountedAmount = $coupon->applyDiscount($sub_total);


            // Create the order
            $order = $this->storeOrderData($validatedData,$sub_total,$discountedAmount,$request);

            // Save billing address
            $this->storeBillingInfo($validatedData,$order);

            // Save medicines (order items)
            $this->storeOrderItems($validatedData,$order);

            // Save assessments and check for correct answers
           $this->storeAssessmentsResult($validatedData,$order);


           //create payment intent
            $this->createPaymentIntent($validatedData,$order);

            // Commit transaction
            DB::commit();

            return  $this->sendResponse([],'Your order has been successfully placed and is currently being processed.',200);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->sendError('Something went wrong while processing your order. Please try again later.',$exception->getMessage(),422);
        }


    }


    /**
     *  Prescription file upload
     */
    private function uploadPrescription(Request $request)
    {
        if ($request->hasFile('prescription')) {
            $file = $request->file('prescription');
            $folder = 'prescriptions';
            $name = 'prescription_' . time() .Str::uuid();

            // Call the fileUpload method
           return  Helper::fileUpload($file, $folder, $name);
        }
        return null;
    }

    /**
     * Calculate total amount
     */
    private function storeOrderData($validatedData,$sub_total,$discountedAmount,$request)
    {
       $coupon = Coupon::where('code', $validatedData['code'])->first();

       return Order::create([
            'uuid'         => substr((string) Str::uuid(), 0, 8),
            'user_id'      => auth()->id(),
            'treatment_id' => $validatedData['treatment_id'],
            'coupon_id'    => $coupon->id ?? null,
            'tracked'      => !empty($validatedData['royal_maill_tracked_price']),
            'royal_mail_tracked_price' => $validatedData['royal_maill_tracked_price'],
            'sub_total'    => $sub_total, //sub total
            'discount'     => $sub_total - ($discountedAmount ?? 0) ,
            'total_price'  => $discountedAmount ?? null,
            'subscription' => $validatedData['subscription'],
            'prescription' => $this->uploadPrescription($request),
            'status'       => 'pending',
        ]);
    }

    /**
     * Store billing info
     */
    private function storeBillingInfo($validatedData,$order)
    {
         BilingAddress::create([
            'order_id'   => $order->id,
            'name'       => $validatedData['name'],
            'email'      => $validatedData['email'],
            'address'    => $validatedData['address'],
            'contact'    => $validatedData['contact'],
            'city'       => $validatedData['city'],
            'postcode'   => $validatedData['postcode'],
            'gp_number'  => $validatedData['gp_number'],
            'gp_address' => $validatedData['gp_address'],
        ]);
    }

    /**
     * store order items
     */
    private function storeOrderItems($validatedData,$order)
    {
        foreach ($validatedData['medicines'] as $medicineData) {
            $orderItem = new order_item([
                'order_id'    => $order->id,
                'medicine_id' => $medicineData['medicine_id'],
                'quantity'    => $medicineData['quantity'],
                'unit_price'  => $medicineData['unit_price'],
                'total_price' => $medicineData['total_price'],
            ]);
            $order->orderItems()->save($orderItem);
        }
    }

    /**
     * Store assessment result
     */
    private function storeAssessmentsResult($validatedData,$order)
    {
        foreach ($validatedData['assessments'] as $assessmentData) {
            $assessment = Assessment::find($assessmentData['assessment_id']);
            $selectedOption = $assessmentData['selected_option'];

            // Check if the selected option matches the correct answer
            $isCorrect = $assessment->answer === $selectedOption;
            $result = $isCorrect ? 'correct' : 'incorrect';

            // Save the assessment result
            AssessmentResult::create([
                'order_id'        => $order->id,
                'treatment_id'    => $validatedData['treatment_id'],
                'assessment_id'   => $assessmentData['assessment_id'],
                'selected_option' => $selectedOption,
                'result'          => $assessment->answer == null ? 'correct' : $result,
                'notes'           => $assessmentData['notes'] ?? null,
            ]);
        }
    }

    /**
     * create payment intent
     */
    private function createPaymentIntent($validatedData,$order)
    {
        //find order with order id
        $orderData = Order::find($order->id);

        //current  auth
        $user =  Auth::user();
        //meta data
        $metadata = [
            'order_uuid' => $orderData->uuid,
            'user_id'    => $user->id,
        ];

        // Create a payment intent with the calculated amount and metadata
        $paymentIntent = PaymentIntent::create([
            'amount' =>  $orderData->total_price * 100, // Stripe accepts amounts in cents
            'currency' => 'usd',
            'metadata' => $metadata,
            'payment_method' => $validatedData['payment_method_id'],
            'customer' => $user->stripe_customer_id, // Add the customer ID here
            'confirm' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
        ]);

        // Update the order with payment intent details
        $orderData->update([
            'stripe_payment_id' => $paymentIntent->id,
        ]);


        //create subscription if subscripiton is true
        if($validatedData['subscription']){
           $subscription =  $this->createSubscription($validatedData,$order,$paymentIntent);

           //update order data
            $orderData->update([
                'subscription_id' => $subscription->id,
            ]);

        }


    }



    /**
     * create subscription if Request -> subscription is true
     */
    private function createSubscription($validatedData,$order,$paymentIntent)
    {

        dd("validatedata: " .$validatedData);
        //get current user
        $user = auth()->user();
        // Check if Stripe customer exists in the database
        // Retrieve the existing customer on Stripe
        $customer = Customer::retrieve($user->stripe_customer_id);
        if (empty($customer)) {
            return $this->sendError('Please add a payment method',[]);
        }

        $decodedResponse = json_decode($this->checkCustomerHasPaymentMethod()->getContent(), true);
        // dd($decodedResponse);

        if (!$decodedResponse['status']) {
            return $decodedResponse;
        }



        // Retrieve all active products
        $productRetrive = \Stripe\Product::all([
            'active' => true, // Filter active products
        ]);


        // Filter products based on metadata
        $filteredProducts = array_filter($productRetrive->data, function ($product) {
            return isset($product->metadata['shop_id']) && $product->metadata['shop_id'] == 1;
        });




        // Now check if there are any filtered products
        if (!empty($filteredProducts)) {
            // Access the first filtered product (if any)
            $product = array_values($filteredProducts)[0]; // This will get the first element
        } else {
            // Create a new product if no matching product is found
            $product = \Stripe\Product::create([
                'name' => "pharmacy id 1",
                'metadata' => [
                    'shop_id' => 1, // show id
                ],
            ]);
        }

        if (!is_object($product)) {
            Log::error('product retrieve failed');
            return $this->sendError('Product retrieval failed.', []);
        }


        // Create Subscription
        $subscription = Subscription::create([
            'customer' => $user->stripe_customer_id,
            'items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product' => $product->id,
                    'unit_amount' => $order->sub_total * 100, // Amount in cents
                    'recurring' => ['interval' => 'month'], // Monthly subscription
                ],
            ]],
            'metadata' => [
                'order_id' => $order->uuid, // Custom metadata
                'user_id' => $user->id,
            ],
            'expand' => ['latest_invoice.payment_intent'],
        ]);

        // Ensure subscription was successfully created
        if (!isset($subscription->id)) {
            return $this->sendError('Subscription creation failed.', []);
        }

//        return $subscription;
        dd($subscription);
    }


    /**
     * Check customers has payment method
     */
    private function checkCustomerHasPaymentMethod()
    {
        $user = auth()->user();
        if (!$user?->stripe_customer_id) {
            return  $this->sendError('Please add your cart.');
        }

        $customer = Customer::retrieve($user->stripe_customer_id);
        if (empty($customer)) {
            return  $this->sendError('Please add your cart.',[]);
        }

        if (!empty($customer->invoice_settings?->default_payment_method)) {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $paymentMethod = $stripe->customers->retrievePaymentMethod($customer->id, $customer->invoice_settings?->default_payment_method, []);
            if (!$paymentMethod) {
                return  $this->sendError('Please add your cart.',[]);
            } else {
                return $this->sendResponse($paymentMethod, 'Customer has payment method');

            }
        }

        return  $this->sendError('Please add your cart.',[]);
    }

}
