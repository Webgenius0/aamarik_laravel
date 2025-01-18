<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryInfoResource extends JsonResource
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
            'description' => $this->description,
            'note' => $this->note,
            'option_name' => $this->option_name,
            'option_sub_description' => $this->option_sub_description,
            'option_value' => $this->option_value
        ];
    }
}
