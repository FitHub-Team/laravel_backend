<?php

namespace App\Services\Coach;

use App\Helper\ImageHelper;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Models\CoachProfile;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
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
  public function updateProfilePhoto(User $user, UploadedFile $image)
  {
    try {
      return DB::transaction(function () use ($user, $image) {

        $profile = $user->coachProfile;
  
        if (!$profile) {
          return null;
        }
        $photoPath = ImageHelper::update(
          $image,
          $profile->profile_photo,
          'coach_avatar'
        );
      //  dd($photoPath);

        // Update profile photo
        $profile->update([
          'profile_photo' => $photoPath,
        ]);

        return $user->fresh('coachProfile');
      });
    } catch (Exception $e) {

      Log::error(
        'ProfileService Update Profile Photo Error: ' . $e->getMessage()
      );

      throw new Exception(
        'فشلت عملية تحديث صورة الملف الشخصي: ' . $e->getMessage()
      );
    }
  }
  public function deleteProfilePhoto(User $user)
  {
    try {
      $profile = $user->coachProfile;

      if (!$profile) {
        return null;
      }

       ImageHelper::delete($profile->profile_photo);

      $profile->update([
        'profile_photo' => null,
      ]);

      return $user->fresh('coachProfile');
    } catch (Exception $e) {
      Log::error(
        'ProfileService Delete Profile Photo Error: ' . $e->getMessage()
      );

      throw new Exception(
        'فشلت عملية حذف صورة الملف الشخصي: ' . $e->getMessage()
      );
    }
  }
}
