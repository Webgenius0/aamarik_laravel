<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatmentRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',

            // Validation for categories
            'categories' => 'nullable|array',
            'categories.*.title' => 'nullable|string|max:255',
            'categories.*.icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',

            // Validation for treatment details
            'details' => 'nullable|array',
            'details.*.title' => 'nullable|string|max:255',
            'details.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',

            // Validation for detail items
            'detail_items' => 'nullable|array',
            'detail_items.*.title' => 'nullable|string|max:255',

            // Validation for about section
            'about' => 'nullable|array',
            'about.*.title' => 'nullable|string|max:255',
            'about.*.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico,bmp,svg|max:2048',
            'about.*.short_description' => 'nullable|string|max:1000',

            // Validation for FAQs
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'nullable|string|max:255',
            'faqs.*.answer' => 'nullable|string|max:5000',

            // Validation for medicines
            'medicines' => 'required|array',
            'medicines.*' => 'exists:medicines,id',

            // Validation for assessments
            'assessments' => 'nullable|array',
            'assessments.*.question' => 'required|string|max:500',
            'assessments.*.option1' => 'required|string|max:255',
            'assessments.*.option2' => 'required|string|max:255',
            'assessments.*.option3' => 'nullable|string|max:255',
            'assessments.*.option4' => 'nullable|string|max:255',
            'assessments.*.answer' => 'required|string|max:255',
            'assessments.*.note' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The treatment name is required.',
            'avatar.image' => 'The avatar must be an image file.',
            'medicines.required' => 'Please select at least one medicine.',
            'medicines.*.exists' => 'One or more selected medicines are invalid.',
            'assessments.*.question.required' => 'Each assessment must have a question.',
            'assessments.*.option1.required' => 'Option 1 is required for each assessment.',
            'assessments.*.option2.required' => 'Option 2 is required for each assessment.',
            'assessments.*.answer.required' => 'Please specify the correct answer for the assessment.',
        ];
    }
}
