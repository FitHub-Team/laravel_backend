<?php

namespace App\Services\Coach;

use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Models\CoachProfile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
  public function updateProfilePhoto(User $coach, string $photoPath)
  {
    try {
      return DB::transaction(function () use ($coach, $photoPath) {

        $profile = $coach->coachProfile;

        if (!$profile) {
          return null;
        }

        // Delete old profile photo
        if (
          $profile->profile_photo &&
          Storage::disk('public')->exists($profile->profile_photo)
        ) {
          Storage::disk('public')->delete($profile->profile_photo);
        }

        // Update profile photo
        $profile->update([
          'profile_photo' => $photoPath,
        ]);

        return $profile->fresh();
      });
    } catch (Exception $e) {

      Log::error(
        'CoachService Update Profile Photo Error: ' . $e->getMessage()
      );

      throw new Exception(
        'فشلت عملية تحديث صورة الملف الشخصي: ' . $e->getMessage()
      );
    }
  }
}
