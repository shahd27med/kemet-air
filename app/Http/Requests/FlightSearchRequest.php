<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'origin_airport_id'      => ['required', 'integer', 'exists:airports,id'],
            'destination_airport_id' => ['required', 'integer', 'different:origin_airport_id', 'exists:airports,id'],
            'departure_date'         => ['required', 'date'],
            'return_date'            => ['nullable', 'date', 'after_or_equal:departure_date'],
            'passengers_count'       => ['nullable', 'integer', 'min:1', 'max:9'],
            'class_type'             => ['nullable', Rule::in(['economy', 'business'])],

            // Sidebar filters (all optional, applied on top of the base search)
            'min_price'              => ['nullable', 'numeric', 'min:0'],
            'max_price'              => ['nullable', 'numeric', 'min:0'],
            'departure_window'       => ['nullable', 'array'],
            'departure_window.*'     => [Rule::in(['morning', 'afternoon', 'evening', 'night'])],
            'airline_id'             => ['nullable', 'array'],
            'airline_id.*'           => ['integer', 'exists:airlines,id'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // تعيين القيمة الافتراضية لعدد المسافرين في حال عدم إرسالها من الفلتر الجانبي
        if (! $this->has('passengers_count') || empty($this->input('passengers_count'))) {
            $this->merge([
                'passengers_count' => 1,
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'destination_airport_id.different' => 'Departure and arrival airports must be different.',
            'departure_date.after_or_equal'     => 'Departure date cannot be in the past.',
            'return_date.after_or_equal'        => 'Return date must be on or after the departure date.',
        ];
    }
}