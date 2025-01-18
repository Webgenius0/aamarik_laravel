<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentMedicineResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'medicines' => $this->medicines->map(function ($medicine) {
                return [
                    'id'          => $medicine->id,
                    'title'       => $medicine->title,
                    'description' => $medicine->description,
                    'max_star'    => '4', // Assuming this is a fixed value
                    'review'      => '10', // Assuming this is a fixed value
                    'price'       => $medicine->details->price, // Access price from related details
                    'avatar'      => $medicine->avatar ?? 'uploads/defult-image/productImage.png', // Default avatar if not set
                ];
            }),
        ];
    }
}
