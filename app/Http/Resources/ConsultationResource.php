<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return  [
            'id' => $this->id,
            'title' => $this->title,
            'sub_description' => $this->sub_description,
            'image' => $this->image ?? "uploads/defult-image/consultation.png",
            'features' => [
                'feature1' => $this->feature1,
                'feature2' => $this->feature2,
                'feature3' => $this->feature3,
            ],
            'button' => [
                'name' => $this->button_name,
                'url' => $this->button_url,
            ]
        ];
    }
}
