<?php

namespace App\Services\General;

use App\Models\User;

class BrowseCoach
{
    public function getActiveCoaches(array $filters = [])
    {
        $query = User::query()
            ->where('role', 'coach')
            ->where('is_approved', true)
            ->where('status', 'active')
            ->with('coachProfile');
            
        if (!empty($filters['min_price'])) {
            $query->whereHas('coachProfile', function ($q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            });
        }
        if (!empty($filters['max_price'])) {
            $query->whereHas('coachProfile', function ($q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            });
        }
       
        return $query->paginate(10);
    }
}
