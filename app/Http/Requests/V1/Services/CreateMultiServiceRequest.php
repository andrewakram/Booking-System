<?php

namespace App\Http\Requests\V1\Services;

use App\Enums\DayOfWeek;
use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMultiServiceRequest extends FormRequest
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
            'data.*.location_ids'   => ['required', 'array'],
            'data.*.location_ids.*' => [
                'required', 'integer',
                function ($attribute, $value, $fail) {
                    $exists = Location::where('id', $value)
                        ->where('provider_id', Auth::id())
                        ->exists();

                    if (! $exists) {
                        $fail('The selected location is invalid for this provider.');
                    }
                },
            ],

            'data.*.name'          => 'required|string|max:255',
            'data.*.description'   => 'nullable|string',
            'data.*.category_id'   => 'required|integer|exists:categories,id',
            'data.*.duration'      => 'required|integer|in:30,60,90',
            'data.*.price'         => 'required|numeric|min:0|max:1000000',

        ];
    }
}
