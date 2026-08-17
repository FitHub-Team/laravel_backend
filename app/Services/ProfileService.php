<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    /**
     * Create a new class instance.
     */

    public function store(User $user, array $data)
    {
        $user->profile()->updateOrCreate(
            [],
            $data
        );

        return $user->load('profile');
    }

    public function show(User $user)
    {
        return $user->load('profile');
    }
    public function update(User $user, array $data)
    {
        $profile = $user->profile;

        if (!$profile) {
            return null;
        }

        $profile->update($data);

        return $user->load('profile');
    }
}
