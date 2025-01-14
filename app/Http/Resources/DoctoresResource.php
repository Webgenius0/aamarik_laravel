<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctoresResource extends JsonResource
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
            'name' => $this->name,
            'depertment' => $this->depertment,
            'avatar' => 'uploads/defult-image/Team_1.png',
            'role' => $this->role,
        ];
    }
}
