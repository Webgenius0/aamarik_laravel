<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineDetailsResource extends JsonResource
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
            'id'          => $this->id,
            'title'       => $this->title,
            'max_star'    => '4',
            'price'       => $this->details->price,
            'form'        => $this->details->form,
            'dosage'      => $this->details->dosage,
            'unit'        => $this->details->unit,
            'price'       => $this->details->price,
            'quantity'    => $this->details->quantity,
            'stock_quantity'=> $this->details->stock_quantity,
            'avatars'     => $this->images ? $this->images->map(function ($image) {
                return [
                    'id'          => $image->id,
                    'avatar'      => $image->image,
                ];
            }) : [],
            'features'    => $this->features->map(function ($feature) {
                return [
                    'id'          => $feature->id,
                    'feature'     => $feature->feature,
                ];
            }),
        ];
    }
}
