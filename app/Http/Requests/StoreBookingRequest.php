<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'package_id' => 'required|exists:packages,id',
            'food_type' => 'required|in:with_food,without_food',
            'adult_count' => 'required|integer|min:0',
            'child_count' => 'required|integer|min:0',
            'booking_date' => 'required|date',

            'contact_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'emirate' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'remarks' => 'nullable|string',
        ];
    }
}