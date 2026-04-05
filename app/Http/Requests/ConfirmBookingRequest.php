<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.passport_number' => ['required', 'string', 'max:50'],
            'passengers.*.age' => ['required', 'integer', 'min:1', 'max:120'],
        ];
    }
}
