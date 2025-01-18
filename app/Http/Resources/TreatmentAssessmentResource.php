<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentAssessmentResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'assessments' => $this->assessments->map(function ($assessment) {
                return [
                    'id' => $assessment->id,
                    'question' => $assessment->question,
                    'option1'  => $assessment->option1,
                    'option2'  => $assessment->option2,
                    'option3'  => $assessment->option3,
                    'option4'  => $assessment->option4,
                    'answer'   => $assessment->answer,
                    'note'     => $assessment->note,
                ];
            })
        ];
    }
}
