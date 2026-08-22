<?php

namespace App\Services;

use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Models\CoachProfile;

class CoachService
{
    protected CoachRepositoryInterface $coachRepository;

    public function __construct(CoachRepositoryInterface $coachRepository)
    {
        $this->coachRepository = $coachRepository;
         }
    public function saveCoachProfile(int $userId, array $data): CoachProfile
    {
 return $this->coachRepository->createOrUpdate($userId, $data);

    }
    public function getCoachProfile(int $userId): ?CoachProfile
    {
  return $this->coachRepository->findByUserId($userId);
    }
}