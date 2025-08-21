<?php

namespace App\Http\Resources\V1\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginUserResource extends JsonResource
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
            'access_token'  => $this->access_token,
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'created_at'    => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'role'          => $this->roles,
        ];
    }
}
