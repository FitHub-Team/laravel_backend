<?php

namespace App\Repositories;

use App\Models\CoachProfile;
use App\Repositories\Contracts\CoachRepositoryInterface;

class CoachRepository implements CoachRepositoryInterface
{
    public function createOrUpdate(int $userId, array $data): CoachProfile
    {
        return CoachProfile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    public function findByUserId(int $userId): ?CoachProfile
    {
        return CoachProfile::where('user_id', $userId)->first();
    }
}