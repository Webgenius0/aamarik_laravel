<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'  => $this->name,
            'email' => $this->email,
            'role'  => $this->role,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'address' => $this->address,
            'date_of_birth' => $this->date_of_birth
                ? $this->date_of_birth->format('m/d/Y')
                : null, //Y-m-d to m/d/Y
            'avatar' => $this->avatar,
            'is_subscribe' => false,

        ];
    }
}
