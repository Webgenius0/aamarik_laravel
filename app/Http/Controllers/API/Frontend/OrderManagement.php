<?php

namespace App\Http\Controllers\API\Frontend;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderCheckoutRequest;
use App\Models\Assessment;
use App\Models\AssessmentResult;
use App\Models\BilingAddress;
use App\Models\Order;
use App\Models\order_item;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Webmozart\Assert\Tests\StaticAnalysis\null;

class OrderManagement extends Controller
{
    use apiresponse;
    /**
     * Create order
     */
    public  function  orderCheckout(Request $request)
    {
        // Validate incoming request
        $validation = Validator::make($request->all(), [
            'treatment_id' => 'required|integer',
            'royal_maill_tracked_price' => 'nullable|numeric',
            'subscription' => 'required|boolean',
            'prescription' => 'nullable|nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',

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
        if ($validation->fails()) {
            return $this->sendError('Create order validation error',$validation->errors()->toArray(), 422); // Change the HTTP code if needed
        }


        // Start a transaction to ensure atomicity
      DB::beginTransaction();

        try {
            // Calculate the total price for the medicines
            $totalPrice = 0;
            foreach ($request->medicines as $medicine) {
                $totalPrice += $medicine['total_price'];
            }

            // Create the order
            $order = Order::create([
                'user_id'      => auth()->id(), // Assuming user is authenticated
                'treatment_id' => $request->treatment_id,
                'tracked'      => $request->royal_maill_tracked_price ? true : false,
                'royal_mail_tracked_price' => $request->royal_maill_tracked_price,
                'total_price'  =>  $totalPrice + $request->royal_maill_tracked_price,
                'subscription' => false,
                'prescription' => $this->uploadPrescription($request), // Handle prescription upload
                'status' => 'pending', // Initial order status
            ]);

            // Save billing address
           BilingAddress::create([
               'order_id' => $order->id,
               'name' => $request->name,
               'email' => $request->email,
               'address' => $request->address,
               'contact' => $request->contact,
               'city' => $request->city,
               'postcode' => $request->postcode,
               'gp_number' => $request->gp_number,
               'gp_address' => $request->gp_address,
           ]);

            // Save medicines (order items)
            foreach ( $validation['medicines'] as $medicineData) {
                $orderItem = new order_item([
                    'order_id'    => $order->id,
                    'medicine_id' => $medicineData['medicine_id'],
                    'quantity'    => $medicineData['quantity'],
                    'unit_price'  => $medicineData['unit_price'],
                    'total_price' => $medicineData['total_price'],
                ]);
                $order->orderItems()->save($orderItem);
            }

            // Save assessments and check for correct answers
            foreach ( $validation['assessments'] as $assessmentData) {
                $assessment = Assessment::find($assessmentData['assessment_id']);
                $selectedOption = $assessmentData['selected_option'];

                // Check if the selected option matches the correct answer
                $isCorrect = $assessment->answer === $selectedOption;
                $result = $isCorrect ? 'correct' : 'incorrect';

                // Save the assessment result
                AssessmentResult::create([
                    'order_id'        => $order->id,
                    'treatment_id'    => $request->treatment_id,
                    'assessment_id'   => $assessmentData['assessment_id'],
                    'selected_option' => $assessment->answer == null ? 'correct' : $selectedOption,
                    'result'          => $result,
                    'notes'           => $assessmentData['notes'] ?? null,
                ]);
            }
            // Commit transaction
            DB::commit();

            return  $this->sendResponse([],'Order placed successfully!',200);
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->sendError('Failed ',$exception->getMessage(),422);
        }


    }


    /**
     *  Prescription file upload
     */
    private function uploadPrescription($request)
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
}
