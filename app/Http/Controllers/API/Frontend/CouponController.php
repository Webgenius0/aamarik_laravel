<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    use apiresponse;

    /**
     * Validate and apply the coupon.
     */
    public function  applyCoupon(Request $request)
    {
        // Validate incoming request
        $validator = Validator::make($request->all(), [
            'coupon_code'  => 'required|string|exists:coupons,code',
            'total_amount' => 'required|numeric|min:0',
        ]);
        // If validation fails, return error message
        if ($validator->fails()) {
            return $this->sendError('Validation failed. Please check the provided details and try again.',$validator->errors()->toArray(), 422); // Change the HTTP code if needed
        }

        // Retrieve validated data
        $validatedData = $validator->validated();
        try {
            $coupon = Coupon::where('code', $validatedData['coupon_code'])->first();

            // Check if the coupon is valid
            if (!$coupon->isValid()) {
                return $this->sendError('The coupon is invalid or expired.', [],410);
            }

            // Apply the discount
            $discountedAmount = $coupon->applyDiscount($validatedData['total_amount']);

            // Increment the used count
            $coupon->increment('used_count');

            $response = [
                'original_amount' => $validatedData['total_amount'],
                'discounted_amount' => $discountedAmount,
                'discount_applied' => $validatedData['total_amount'] - $discountedAmount,
            ];

            return $this->sendResponse($response,'Applied successfully.');
        }catch (\Exception $exception){
            return $this->sendError('Something went wrong.', [], 500);
        }
    }
}
