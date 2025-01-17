<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentDetailResource extends JsonResource
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
            'detail' => [
                'id'     => $this->detail->id,
                'title'  => $this->detail->title,
                'avatar' => $this->detail->avatar ?? 'uploads/defult-image/HairTreatment.png',
            ],
            'items' =>  $this->detailItems->map(function ($item) {
                return [
                    'id'     => $item->id,
                    'icon'   => 'uploads/defult-image/yes.png',
                    'title'  => $item->title,
                ];
            }),
        ];
    }
}
