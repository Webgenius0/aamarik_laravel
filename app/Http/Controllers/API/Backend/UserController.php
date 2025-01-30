<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthReviewResource;
use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Models\Review;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use apiresponse;
    /**
     * Get User Reviews
     */
    public function getAuthReview()
    {
        try {
            //get current auth
            $user = Auth::user();
            $reviews = Order::with('user', 'review')->get();
            if($reviews->isEmpty())
            {
                return $this->sendError("No reviews found");
            }
            return $this->sendResponse(AuthReviewResource::collection($reviews), 'Reviews retrieved successfully.');
        }catch (\Exception $exception)
        {
            return $this->sendError('Failed to retrieve reviews.'.$exception->getMessage());
        }
    }
}
