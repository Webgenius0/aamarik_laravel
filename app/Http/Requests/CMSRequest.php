<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CMSRequest extends FormRequest
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
            'title'       => 'nullable|string|max:255',      // Title is required and must be a string with a max length of 255 characters
            'sub_title'   => 'nullable|string|max:255',  // Subtitle is optional and must be a string with a max length of 255 characters
            'description' => 'nullable|string',        // Description is optional and must be a string
            'avatar'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048', // Avatar is optional and must be an image with a max size of 2048KB
            'button_name' => 'nullable|string|max:255', // Button name is optional and must be a string with a max length of 255 characters
            'button_url'  => 'nullable|url',            // Button URL is optional and must be a valid URL
            'type'        => 'nullable|in:banner,personalized,process', // Type is required and must be one of the given options
        ];
    }
}
