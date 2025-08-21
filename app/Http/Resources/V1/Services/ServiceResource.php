<?php

namespace App\Http\Resources\V1\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'duration'      => $this->duration,
            'price'         => $this->price,
            'created_at'    => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'category'      => $this->category,
            'provider'      => $this->provider,
            'locations'     => $this->locations,
        ];
    }
}
