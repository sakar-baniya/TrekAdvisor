<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTrekRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'difficulty' => ['required', Rule::in(['easy', 'moderate', 'difficult', 'extreme'])],
            'duration_days' => ['required', 'integer', 'min:1'],
            'max_altitude' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'remove_gallery_images' => ['nullable', 'array'],
            'remove_gallery_images.*' => ['integer'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.title' => ['nullable', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
        ];
    }
}

