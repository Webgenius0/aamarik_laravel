<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
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
        //get current auth
        $user = Auth::user();
        $reviews = Order::with('user', 'review')->get();
        return response()->json($reviews, 200);


    }
}
