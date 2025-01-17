<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentAboutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
            'treatment_id' => $this->id, //treatment id
            'about' => [
                'id'     => $this->about->id,
                'title'  => $this->about->title,
                'avatar' => $this->about->avatar ?? 'uploads/defult-image/AboutHairLoss.png',
                'short_description' => $this->about->short_description,
            ],
            'faqs' =>  $this->faqs->map(function ($faq) {
                return [
                    'id'      => $faq->id,
                    'question'=> $faq->question,
                    'answer'  => $faq->answer,
                ];
            }),
        ];
    }
}
