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
        if ($this->method() == 'PUT') {
            return [
                'title' => 'string|max:255',
                'address' => 'string|max:300',
                'latitude' => 'string',
                'longitude' => 'string',
                'subtitle' => 'string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
                'information' => 'string',
                'map_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
                'map_url' => 'url',
                'points' => 'integer',
                'puzzle_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
            ];
        } else {
            return [
                'title' => 'required|string|max:255',
                'address' => 'required|string|max:300',
                'latitude' => 'required|string',
                'longitude' => 'required|string',
                'subtitle' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
                'information' => 'required|string',
                'map_image' => 'required|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
                'map_url' => 'required|url',
                'points' => 'required|integer',
                'puzzle_image' => 'required|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:5120',
            ];
        }
    }
}
