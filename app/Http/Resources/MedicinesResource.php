<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicinesResource extends JsonResource
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
             'description' => $this->description,
             'max_star'    => '4',
             'review'      => '10',
             'price'       => $this->details->price,
         ];
    }
}
