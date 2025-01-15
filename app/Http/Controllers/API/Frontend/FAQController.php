<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Traits\apiresponse;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    use apiresponse;
    /**
     * My health need and health care services page faqs
     */
    public  function  index()
    {
        try {
            $faqs = FAQ::where('status','active')->where('type','Supplement')->get();
            if ($faqs->isEmpty()) {
                return $this->sendResponse((object)[], 'FAQ List not found');
            }
            $response = $faqs->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                ];
            });
            return $this->sendResponse($response, 'FAQ List found');
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[], $exception->getCode() );
        }
    }

    /**
     * Faq page
     */
    public  function  faq()
    {
        try {
            // Fetch FAQs where type is not 'Supplement' and status is 'active'
            $faqs = FAQ::where('status', 'active')
                ->where('type', '!=', 'Supplement')
                ->get();

            if ($faqs->isEmpty()) {
                return $this->sendResponse((object)[], 'FAQ List not found');
            }


            // Group by the 'type' field (this can be adjusted if needed)
            $groupedFaqs = $faqs->groupBy('type');


            // Format the response
            $response = $groupedFaqs->map(function ($faqGroup, $category) {
                return [
                    'category' => $category,
                    'questions' => $faqGroup->map(function ($faq) {
                        return [
                            'question' => $faq->question,
                            'answer' => $faq->answer,
                        ];
                    }),
                ];
            })->values();

            // Return the grouped and formatted response
            return $this->sendResponse($response, 'FAQ List found');
        }catch (\Exception $exception){
            return $this->sendError($exception->getMessage(),[],$exception->getCode());
        }
    }
}
