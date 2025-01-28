<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
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
            'id'     => $this->id,
            'date'   => $this->date->format('Y-m-d'),
            'time'   => $this->time->format('h:i A'),
            'link'   => $this->link,
            'user'   => $this->user->name,
            'status' => $this->status,
        ];
    }
}
