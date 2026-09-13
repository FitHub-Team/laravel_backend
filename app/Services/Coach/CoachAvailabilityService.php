<?php

namespace App\Services\Coach;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class AvailabilityService
{
    public function getAvailabilities(User $coach)
    {
        return $coach->availabilities;
    }

    public function updateAvailabilities(User $coach, array $availabilitiesData)
    {
        return DB::transaction(function () use ($coach, $availabilitiesData) {
            $coach->availabilities()->delete();

            foreach ($availabilitiesData as $slot) {
                $coach->availabilities()->create($slot);
            }

            return $coach->availabilities;
        });
    }
}