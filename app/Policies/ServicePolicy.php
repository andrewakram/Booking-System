<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;

class ServicePolicy
{
    public function view(User $user, Service $service): bool
    {
        return $user->providerServices()
            ->where('teams.id', $service->provider_id)
            ->exists();
    }

    public function create(User $user, Service $service): bool
    {
        return $user->providerServices()
            ->where('users.id', $service->provider_id)
            ->exists();
    }
}
