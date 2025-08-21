<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ServicePolicy
{
    /**
     * All users (admin, provider, customer) can view services.
     */
    public function view(User $user, Service $service): bool
    {
        return true;
    }

    /**
     * Only providers can create services.
     */
    public function create(): bool
    {
        return auth()->user()->roles->first() && auth()->user()->roles->first()->name === 'provider';
    }
}
