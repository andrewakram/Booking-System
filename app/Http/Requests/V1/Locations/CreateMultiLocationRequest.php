<?php

namespace App\Http\Requests\V1\Locations;

use App\Enums\DayOfWeek;
use App\Enums\TimeZone;
use App\Models\Location;
use Illuminate\Foundation\Http\FormRequest;

class CreateMultiLocationRequest extends FormRequest
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

            'data.*.name'       => 'required|string|max:255',
            'data.*.time_zone'  => 'required|string|in:' . implode(',', TimeZone::getValues()),
            'data.*.lat'        => 'nullable|string|max:255',
            'data.*.lng'        => 'nullable|string|max:255',

            'data.*.availability'               => 'required|array',
            'data.*.availability.*.day_of_week' => 'required|in:' . implode(',', DayOfWeek::getValues()),
            'data.*.availability.*.start_time'  => 'required|date_format:H:i:s',
            'data.*.availability.*.end_time'    => 'required|date_format:H:i:s',

            'data.*.availability_override'              => 'required|array',
            'data.*.availability_override.*.date'       => 'nullable|date_format:Y-m-d',
            'data.*.availability_override.*.start_time' => 'required|date_format:H:i:s',
            'data.*.availability_override.*.end_time'   => 'required|date_format:H:i:s',


        ];
    }
}
