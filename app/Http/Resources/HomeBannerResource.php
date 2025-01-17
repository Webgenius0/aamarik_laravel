<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeBannerResource extends JsonResource
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
            "sub_title"   => $this->sub_title,
            "avatar"      => $this->avatar ?? 'uploads/defult-image/home_banner.png',
            "button_name" => $this->button_name,
            "button_url"  => $this->button_url,
            "type"        => $this->type,
        ];
    }
}
