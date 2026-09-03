<?php

namespace App\Services\General;

use App\Models\User;

class BrowseCoach
{
    public function getActiveCoaches(array $filters = [])
    {
        // dd($filters);
        $query = User::query()
            ->select([
                'id',
                'full_name',
                'email'
            ])
            ->where('role', 'coach')
            ->whereHas('coachProfile', function ($q) {
                $q->where('is_approved', true)
                    ->where('status', 'active');
            })
            ->with([
                'coachProfile:id,user_id,specialization,experience,location,price,profile_photo',
            ]);


        // Search by coach name
        if (!empty($filters['search'])) {
            $query->where('full_name', 'like', '%' . $filters['search'] . '%');
        }
        // Search by Minimum price
        if (!empty($filters['min_price'])) {
            $query->whereHas('coachProfile', function ($q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            });
        }
        // Search by Maximum prices
        if (!empty($filters['max_price'])) {
            $query->whereHas('coachProfile', function ($q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            });
        }

        return $query->paginate(10);
    }
    public function getCoachById(int $id)
    {
        return User::where('id', $id)
            ->where('role', 'coach')
            ->whereHas('coachProfile', function ($q) {
                $q->where('is_approved', true)
                    ->where('status', 'active');
            })
            ->with([
                'coachProfile:id,user_id,specialization,experience,location,price,profile_photo',
            ])
            ->first();
    }
    
}
