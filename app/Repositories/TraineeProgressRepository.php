<?php

namespace App\Repositories;

use App\Models\TraineeProgress;
use App\Repositories\Contracts\TraineeProgressRepositoryInterface;

class TraineeProgressRepository implements TraineeProgressRepositoryInterface
{
    public function createProgress(array $data)
    {
        return TraineeProgress::create($data);
    }

    public function getTraineeProgress(int $traineeId)
    {
        return TraineeProgress::with('exercises.workoutExercise')
            ->where('trainee_id', $traineeId)
            ->latest('recorded_at')
            ->get();
    }

    public function updateProgress(int $progressId, array $data)
    {
        $progress = TraineeProgress::findOrFail($progressId);
        $progress->update($data);

        return $progress->load('exercises.workoutExercise');
    }
    public function findProgress(int $progressId)
    {
        return TraineeProgress::findOrFail($progressId);
    }
    public function getWeightProgressChart(int $traineeId)
{
    $records = TraineeProgress::where('trainee_id', $traineeId)
        ->orderBy('recorded_at', 'desc')
        ->get();

    if ($records->isEmpty()) {
        return [
            'current_weight' => 0,
            'weight_change' => 0,
            'chart_data' => [],
            'recent_logs' => []
        ];
    }

    $latestRecord = $records->first();
    $currentWeight = (float) $latestRecord->weight;

    // حساب التغير عن القياس السابق
    $previousRecord = $records->skip(1)->first();
    $weightChange = 0;
    if ($previousRecord) {
        $weightChange = round($currentWeight - (float) $previousRecord->weight, 1);
    }

    // تجهيز بيانات الشارت (مرتبة تصاعدياً حسب التاريخ)
    $chartData = TraineeProgress::where('trainee_id', $traineeId)
        ->orderBy('recorded_at', 'asc')
        ->get()
        ->map(function ($item) {
            return [
                'date' => optional($item->recorded_at)->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                'weight' => (float) $item->weight,
            ];
        });

   
    $recentLogs = $records->map(function ($item) {
        return [
            'id' => $item->id,
            'weight' => (float) $item->weight,
            'date' => optional($item->recorded_at)->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
            'formatted_date' => optional($item->recorded_at)->translatedFormat('d F Y') ?? $item->created_at->translatedFormat('d F Y'),
        ];
    });

    return [
        'current_weight' => $currentWeight,
        'weight_change' => $weightChange,
        'chart_data' => $chartData,
        'recent_logs' => $recentLogs,
    ];
}
}
