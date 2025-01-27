<?php

namespace App\Http\Controllers\API\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\userOrderDetailsResource;
use App\Http\Resources\userOrdersResource;
use App\Models\Order;
use App\Models\Review;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderManagementController extends Controller
{
    use apiresponse;

    /**
     * Get user orders based on role with filtering and sorting.
     */
    public function index(Request $request)
    {
        $column  = $request->query('column', 'uuid');
        $search  = $request->query('value', '');
        $sort    = $request->query('sort', 'asc'); // Default asc
        $perPage = $request->input('per_page', 10); // Number of items per page (default to 10)
        $page    = $request->input('page', 1); // Page number (default to 1)

        try {
            // Validate sort parameter
            if (!in_array($sort, ['asc', 'desc'])) {
                $sort = 'asc'; // Default to 'asc' if invalid
            }

            // Get the authenticated user
            $user = auth()->user();

            if (!$user) {
                return $this->sendError('Unauthorized access.', [], 401);
            }

            // Base query for orders
            $query = Order::query();

            // Apply role-based filters
            switch ($user->role) {
                case 'user':
                    // Users only see their own orders with pagination, no filtering or sorting
                    $query->where('user_id', $user->id);
                    break;
                case 'doctor':
                    // Doctors can view all orders with full filtering and sorting without pending status
                    $query->whereNotIn('status', ['pending']);
                    break;
                case 'pharmacist':
                    // Pharmacists see orders with status other than 'pending' or 'paid'
                    $query->whereNotIn('status', ['pending', 'paid']);
                    break;
                default:
                    return $this->sendError('Invalid role.', [], 403);
            }

            // Apply search filter only if the user is not a regular user
            if ($user->role !== 'user' && !empty($search) && !empty($column)) {
                $query->where($column, 'LIKE', "%$search%");
            }

            // Apply sorting only if the user is not a regular user
            if ($user->role !== 'user') {
                $query->orderBy('status', $sort);
            }

            // Fetch paginated orders
            $orders = $query->paginate($perPage, ['*'], 'page', $page);

            $response = [
                'orders' => userOrdersResource::collection($orders),
                'pagination' => [
                    'total'        => $orders->total(),
                    'per_page'     => $orders->perPage(),
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                    'from'         => $orders->firstItem(),
                    'to'           => $orders->lastItem()
                ]
            ];
            // Return the orders as a resource collection
            return $this->sendResponse($response, 'Orders retrieved successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), [], 422);
        }
    }

   /**
    * Show order details
    */
   public function show($id)
   {
       try {
           $order = Order::with([
               'user',
               'treatment',
               'orderItems',
               'review',
               'billingAddress'
           ])->where('uuid', $id)->first();


           if (!$order) {
               return response(['message' => 'Order not found'], 404);
           }


           return  $this->sendResponse(new userOrderDetailsResource($order), 'Order Details retrieved successfully.');
       }catch (\Exception $exception){
           return $this->sendError($exception->getMessage(), [],422);
       }
   }

   /**
    *  Order review store
    */
   public function storeOrderReview(Request $request,$id)
   {
       // Validate incoming request
       $validator = Validator::make($request->all(), [
           'rating' => 'required|string|min:1|max:5',
           'review' => 'required|string',
       ]);
       // If validation fails, return error message
       if ($validator->fails()) {
           return $this->sendError('Validation error:'.$validator->errors()->first(),[], 422); // Change the HTTP code if needed
       }

       // Retrieve validated data
       $validatedData = $validator->validated();

       try {
           //find order with order uuid
           $oder = Order::where('uuid', $id)->first();
           if (!$oder) {
               return $this->sendResponse([], 'Order not found', 200);
           }
           if ($oder->status != 'delivered') {
               return $this->sendResponse([], 'Order status is not delivered', 200);
           }
           if ($oder->review) {
               return $this->sendResponse([], 'Order status is already reviewed', 200);
           }

           Review::create([
               'order_id' => $oder->id,
               'user_id' => auth()->user()->id,
               'rating' => $validatedData['rating'],
               'review' => $validatedData['review'],
           ]);
           return $this->sendResponse([], 'Order review successfully.', 200);

       }catch (\Exception $exception){
           return $this->sendError('Failed to send order review', [], 422);
       }
   }


    /**
     * Get treatments assessments result
     */
    public function getAssessmentResult()
    {
        // Get the currently authenticated user
        $user = auth()->user();
        if (!$user) {
            return $this->sendError('Unauthorized access.', [], 401);
        }

        // Fetch orders along with related treatment and assessment data
        $orders = Order::with(['treatment', 'assessmentsResults.assessment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        // Map the orders to a structured response
        $result = $orders->map(function ($order) {
            // Count correct and wrong answers
            $totalCorrect = $order->assessmentsResults->where('result', 'correct')->count();
            $totalWrong = $order->assessmentsResults->where('result', '!=', 'correct')->count();
            $totalAssessment = $order->assessmentsResults->count();

            return [
                'Treatment' => $order->treatment->name,
                'Total_correct' => $totalCorrect,
                'Total_wrong' => $totalWrong,
                'Total_assessment' => $totalAssessment,
                'Order_uuid' => $order->uuid,
                'Assessment_results' => $order->assessmentsResults->map(function ($result) {
                    $assessmentData = [
                        'assessment' => $result->assessment->question,
                        'is_correct' => $result->result === 'correct',
                        'selected_option' => $result->selected_option,
                    ];

                    // Add assessment options dynamically if they exist
                    foreach (['option1', 'option2', 'option3', 'option4'] as $optionKey) {
                        if (!empty($result->assessment->$optionKey)) {
                            $assessmentData[$optionKey] = $result->assessment->$optionKey;
                        }
                    }

                    // Add the note if it exists
                    if (!empty($result->assessment->note)) {
                        $assessmentData['note'] = $result->assessment->note;
                        $assessmentData['note_answer'] = $result->notes;
                    }

                    return $assessmentData;
                }),
            ];
        });

        return $this->sendResponse($result, 'Assessment result retrieved successfully.');
    }
}
