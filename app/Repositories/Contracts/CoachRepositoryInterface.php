<?php

namespace App\Repositories\Contracts;

use App\Models\CoachProfile;

interface CoachRepositoryInterface
{
    public function createOrUpdate(int $userId, array $data): CoachProfile;
    public function findByUserId(int $userId): ?CoachProfile;
}