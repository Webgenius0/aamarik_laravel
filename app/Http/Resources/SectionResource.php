<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id'    => $this->id,
            'title' => $this->title,
            'cards' => $this->sectionCards ? $this->sectionCards->map(function ($card) {
                return [
                    'id'        => $card->id,
                    'title'     => $card->title,
                    'sub_title' => $card->sub_title,
                    'avatar'    => $card->avatar,
                ];
            }) : []
        ];
    }
}
