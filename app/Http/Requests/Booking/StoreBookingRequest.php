<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'departure_id' => ['required', 'exists:departures,id'],
            'total_passengers' => ['required', 'integer', 'min:1', 'max:10'],
        ];
    }
}
