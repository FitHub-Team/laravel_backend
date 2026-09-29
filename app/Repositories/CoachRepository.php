<?php

namespace App\Repositories;

use App\Models\CoachProfile;
use App\Models\skill;
use App\Repositories\Contracts\CoachRepositoryInterface;

class CoachRepository implements CoachRepositoryInterface
{
    public function createOrUpdate(int $userId, array $data): CoachProfile
    {
        $hasSkills = array_key_exists('skills', $data);
        $hasCertifications = array_key_exists('certifications', $data);

        $skills = $data['skills'] ?? [];
        $certifications = $data['certifications'] ?? [];

        unset($data['skills'], $data['certifications']);

        $profile = CoachProfile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        // Skills
        $skillIds = collect($skills)
            ->map(function ($skill) {

                // مهارة موجودة مسبقًا
                if (!empty($skill['id'])) {
                    return $skill['id'];
                }

                // مهارة جديدة
                return skill::firstOrCreate([
                    'name' => $skill['name'],
                ])->id;
            })
            ->filter()
            ->values()
            ->toArray();

        $profile->skills()->sync($skillIds);


        // Certifications
        if ($hasCertifications) {
            $profile->certifications()->delete();

            foreach ($certifications as $certification) {
                $profile->certifications()->create([
                    'title' => $certification['title'],
                    'issuer' => $certification['issuer'] ?? null,
                    'year' => $certification['year'] ?? null,
                ]);
            }
        }

        return $profile->load([
            'skills',
            'certifications',
        ]);
    }

    public function findByUserId(int $userId): ?CoachProfile
    {
        return CoachProfile::with([
            'skills',
            'certifications',
        ])
            ->where('user_id', $userId)
            ->first();
    }
}
