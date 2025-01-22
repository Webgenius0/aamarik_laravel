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
            return $this->sendError($exception->getMessage(), [], 500);
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
            return $this->sendError($exception->getMessage(), [], 500);
        }
    }

    /**
     * Medicine Review with filtering and Average Review
     */
    public function getAverageReviews()
    {
        try {
            // Use the query builder to perform operations directly on the database
            $totalReviews = Review::count();
            $averageRating = $totalReviews > 0 ? Review::avg('rating') : 0;

            // Prepopulate ratings from 5 to 1
            $ratingPercentages = collect(range(5, 1))->map(function ($rating) {
                return [
                    'name' => 'rating_' . uniqid(),
                    'rating' => $rating,
                    'percentage' => 0,
                ];
            });

            // Query to group reviews by rating and count them
            $actualRatings = Review::selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('rating', 'desc')
                ->get();

            // Map the actual ratings to the prepopulated ratings array
            $ratingPercentages = $ratingPercentages->map(function ($rating) use ($actualRatings, $totalReviews) {
                $match = $actualRatings->firstWhere('rating', $rating['rating']);
                if ($match) {
                    $rating['percentage'] = $totalReviews > 0 ? round(($match->count / $totalReviews) * 100, 1) : 0;
                }
                return $rating;
            });

            // Prepare the response data
            $data = [
                'average_rating' => round($averageRating, 1),
                'total_reviews' => $totalReviews,
                'ratings' => $ratingPercentages->values(),
            ];

            return $this->sendResponse($data, 'Average reviews retrieved successfully');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), [], 500);
        }
    }



    public function getReview(Request $request)
    {
        $rating = $request->query('rating'); // Filter by rating
        $sort = $request->query('sort', 'new'); // Sort order (default: new)
        $perPage = $request->input('per_page', 10); // Number of items per page (default to 10)
        $page = $request->input('page', 1); // Page number (default to 1)

        try {
            // Find the medicine and eager load its reviews
            $medicine = Medicine::all();
            if (!$medicine) {
                return $this->sendResponse([], 'Medicine not found');
            }


            $filtering = $this->filteringQuery($rating, $sort, $perPage, $page);

            return $this->getResponse($filtering);
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(), [], 500);
        }
    }

    private function filteringQuery($rating, $sort, $perPage, $page)
    {
        // Build the query for all reviews
        return Review::query()
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
            ->paginate($perPage, ['*'], 'page', $page); // Paginate the results
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
