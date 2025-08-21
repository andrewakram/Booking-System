<?php

namespace App\Http\Requests\V1\Booking;

use App\Enums\DayOfWeek;
use App\Enums\TimeZone;
use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateBookingRequest extends FormRequest
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
            'service_id'    => 'required|integer|exists:services,id',
            'location_id'   => 'required|integer|exists:locations,id',
            'time_zone'     => 'required|string|in:' . implode(',', TimeZone::getValues()),
            'date'          => 'required|date_format:Y-m-d',
            'start_time'    => 'required|date_format:H:i:s',
        ];
    }
}
