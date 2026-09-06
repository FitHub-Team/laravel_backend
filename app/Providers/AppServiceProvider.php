<?php

namespace App\Providers;

use App\Repositories\Contracts\TraineeProgressRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\TraineeProgressRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\CoachRepositoryInterface;
use App\Repositories\CoachRepository;
use App\Repositories\Contracts\NutritionPlanRepositoryInterface;
use App\Repositories\Contracts\WorkoutPlanRepositoryInterface;
use App\Repositories\NutritionPlanRepository;
use App\Repositories\WorkoutPlanRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            CoachRepositoryInterface::class,
            CoachRepository::class
        );
        $this->app->bind(
            WorkoutPlanRepositoryInterface::class,
            WorkoutPlanRepository::class
        );
        $this->app->bind(
            NutritionPlanRepositoryInterface::class,
            NutritionPlanRepository::class
        );
        $this->app->bind(
            TraineeProgressRepositoryInterface::class,
            TraineeProgressRepository::class
        );
    }

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return config('app.frontend_url')
                . '/reset-password?token='
                . $token
                . '&email='
                . $notifiable->email;
        });

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
    }
}
