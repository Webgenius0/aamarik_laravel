<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'address' => 'required|string|max:300',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'subtitle' => 'required|string',
            'image' => 'required|string',
            'information' => 'required|string',
            'map_image' => 'required|string',
            'map_url' => 'required|url',
            'points' => 'required|integer',
            'puzzle_image' => 'required|string',
        ];
    }
}
