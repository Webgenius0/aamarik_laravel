<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishListsResource;
use App\Models\Location;
use Illuminate\Http\Request;

class WishlistContoller extends Controller
{
    /**
     * list of all wishlist
     */
    public function index()
    {
        try {
            //get current user
            $user = auth()->user();
            $wishlists = $user->wishlists()->get();

            //return success response
            return  $this->sendResponse(WishListsResource::collection($wishlists), ' retrieved successfully');
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to retrieved Wishlists', $th->getMessage());
        }
    }

    /**
     * Toggle wishlist item add or remove
     */
    public function toggleWishlist($locationID)
    {
        try {
            $user = auth()->user();

            //find location with id
            $location = Location::find($locationID);
            if (!$location) {
                return $this->sendResponse((object)[], 'Puzzle details not found');
            }

            //check alrady in wishlist
            $wishlist = $user->wishlists()->where('location_id', $locationID)->first();
            if ($wishlist) {
                $wishlist->delete();
                return $this->sendResponse([], 'Puzzle details removed from wishlist');
            } else {
                $user->wishlists()->create(['location_id' => $locationID]);
                return $this->sendResponse([], 'Puzzle details added to wishlist');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError('Failed to retrieved Puzzle Details', $th->getMessage());
        }
    }
}
