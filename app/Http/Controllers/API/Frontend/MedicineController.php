<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\MedicineDetailsResource;
use App\Http\Resources\MedicinesResource;
use App\Http\Resources\ReviewResource;
use App\Models\Medicine;
use App\Models\Review;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    use apiresponse;

    /**
     * List of medicin - HealthCare Services->Our Test Kit
     */
    public function index()
    {
        try {
            //Get medicines that are active and have stock available.
            $medicines = Medicine::with(['details' => function ($query) {
                $query->where('stock_quantity', '>', 0);
            }])
                ->where('status', 'active')
                ->latest()->limit(4)->get();

            if ($medicines->isEmpty()) {
                return $this->sendResponse((object)[], 'Medicine List not found');
            }

            return $this->sendResponse(MedicinesResource::collection($medicines), 'Medicines retrieved successfully');
        } catch (\Exception $exception) {
            $statusCode = is_numeric($exception->getCode()) ? $exception->getCode() : 500;
            return $this->sendError($exception->getMessage(), [], $statusCode);
        }
    }

    /**
     * Show medicine details
     */
    public function show($medicineID)
    {
        try {
            $medicine = Medicine::with(['details', 'features'])->find($medicineID);

            if (!$medicine) {
                return $this->sendResponse([], 'Medicine not found');
            }

            return $this->sendResponse(new MedicineDetailsResource($medicine), 'Medicine retrieved successfully');
        } catch (\Exception $exception) {
            $statusCode = is_numeric($exception->getCode()) ? $exception->getCode() : 500;
            return $this->sendError($exception->getMessage(), [], $statusCode);
        }
    }

    /**
     * Medicine Review with filtering and Average Review
     */

    public function getAverageReviews($medicineID)
    {
        try {
            // Fetch reviews specific to the given medicine ID
            $reviews = Review::where('medicine_id', $medicineID);

            // Total number of reviews for the medicine
            $totalReviews = $reviews->count();

            // Average rating calculation (if there are reviews)
            $averageRating = $totalReviews > 0 ? $reviews->avg('rating') : 0;

            // Calculate rating percentages grouped by rating value
            $ratingPercentages = $reviews->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get()
                ->map(function ($item) use ($totalReviews) {
                    // Generate a random unique name for each rating (You can customize this logic further)
                    $name = 'rating_' . uniqid();

                    return [
                        'name' => $name, // Randomly generated name for each rating
                        'rating' => $item->rating, // Rating value
                        'percentage' => $totalReviews > 0
                            ? round(($item->count / $totalReviews) * 100, 1)
                            : 0, // Percentage of total reviews
                    ];
                });

            $data = [
                'average_rating' => round($averageRating, 1), // Rounded to one decimal place
                'total_reviews' => $totalReviews,
                'ratings' => $ratingPercentages // Correctly formatted as an array
            ];

            return $this->sendResponse($data, 'Average reviews retrieved successfully');
        } catch (\Exception $exception) {
            $statusCode = is_numeric($exception->getCode()) ? $exception->getCode() : 500;
            return $this->sendError($exception->getMessage(), [], $statusCode);
        }
    }




    public function getReview(Request $request, $medicineID)
    {
        $rating = $request->query('rating'); // Filter by rating
        $sort = $request->query('sort', 'new'); // Sort order (default: new)
        $perPage = $request->input('per_page', 10); // Number of items per page (default to 10)
        $page = $request->input('page', 1); // Page number (default to 1)

        try {
            // Find the medicine and eager load its reviews
            $medicine = Medicine::find($medicineID);
            if (!$medicine) {
                return $this->sendResponse([], 'Medicine not found');
            }


            $filtering = $this->filteringQuery($medicineID, $rating, $sort, $perPage, $page);

            return $this->getResponse($filtering);
        }catch (\Exception $exception){
            $statusCode = is_numeric($exception->getCode()) ? $exception->getCode() : 500;
            return $this->sendError($exception->getMessage(), [], $statusCode);
        }
    }

    private function filteringQuery($medicineID, $rating, $sort, $perPage, $page)
    {
        // Build the query for reviews
       return Review::where('medicine_id', $medicineID)
            ->when($rating, function ($query, $rating) {
                return $query->where('rating', $rating); // Filter by rating if provided
            })
            ->when($sort === 'high-rating', function ($query) {
                return $query->orderBy('rating', 'desc'); // Sort by highest rating
            })
            ->when($sort === 'low-rating', function ($query) {
                return $query->orderBy('rating', 'asc'); // Sort by lowest rating
            })
            ->when($sort === 'most-helpful', function ($query) {
                return $query->orderBy('helpful_count', 'desc'); // Sort by most helpful
            })
            ->when($sort === 'least-helpful', function ($query) {
                return $query->orderBy('helpful_count', 'asc'); // Sort by least helpful
            })
            ->when($sort === 'alphabetical', function ($query) {
                return $query->orderBy('review', 'asc'); // Sort alphabetically by review text
            })
            ->when($sort === 'non-rated', function ($query) {
                return $query->whereNull('rating'); // Filter reviews without ratings
            })
            ->when($sort === 'old', function ($query) {
                return $query->orderBy('created_at', 'asc'); // Sort by oldest reviews
            }, function ($query) {
                return $query->orderBy('created_at', 'desc'); // Default to newest reviews
            })
            ->with('user') // Fetch related user information
            ->paginate($perPage); // Paginate the results
    }

    private function getResponse($filtering)
    {
        return response()->json([
            'status' => true,
            'message' => $filtering->isEmpty() ? "Medicine Review not found" : "Medicine Review retrieved successfully!",
            'code' => 200,
            'data' => ReviewResource::collection($filtering),
            'pagination' => [
                'total'         => $filtering->total(),
                'current_page'  => $filtering->currentPage(),
                'per_page'      => $filtering->perPage(),
                'last_page'     => $filtering->lastPage(),
                'from'          => $filtering->firstItem(),
                'to'            => $filtering->lastItem(),
            ]
        ], 200);
    }
}
