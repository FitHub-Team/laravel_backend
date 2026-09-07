<?php

namespace App\Repositories\Contracts;

interface TraineeProgressRepositoryInterface
{
    public function createProgress(array $data);

    public function getTraineeProgress(int $traineeId);

    public function updateProgress(int $progressId, array $data);
    public function updateProgressPhoto(int $progressId, string $photoPath);
}
