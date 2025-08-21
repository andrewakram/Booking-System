<?php

namespace App\Http\Resources\V1\Booking;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'service_id'    => $this->service_id,
            'customer_id'   => $this->customer_id,
            'provider_id'   => $this->provider_id,
            'location_id'   => $this->location_id,
            'date'          => $this->date,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
            'status'        => $this->status,
            'service'       => $this->service,
            'provider'      => $this->provider,
            'location'      => $this->location,
        ];
    }
}
