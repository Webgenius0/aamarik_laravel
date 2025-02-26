<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalizedResource extends JsonResource
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
            "id"          => $this->id,
            "title"       => $this->title,
            "description" => $this->description,
            "avatar"      => $this->avatar ?? 'uploads/defult-image/personalized.png',
            "type"        => $this->type,
        ];
    }
}
