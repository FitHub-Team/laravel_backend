<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Google\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private UserRepositoryInterface $userRepository,
        // private EmailVerificationService $emailVerificationService
    ) {}
    public function register(array $data)
    {
        try {
            return DB::transaction(function () use ($data) {

                $data['password'] = Hash::make($data['password']);

                if (User::where('email', $data['email'])->exists()) {
                    throw ValidationException::withMessages([
                        'email' => ['Email already registered'],
                    ]);
                }

                $user = $this->userRepository->create([
                    'full_name' => $data['full_name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'role' => $data['role'],
                ]);


                // User registration

                if ($data['role'] === 'user') {

                    $profile = $user->userProfile()->create([
                        'gender' => $data['gender'] ?? null,
                        'date_of_birth' => $data['date_of_birth'] ?? null,
                        'height' => $data['height'] ?? null,
                        'weight' => $data['weight'] ?? null,

                        'goal_id' => $data['goal_id'] ?? null,
                        'activity_level_id' => $data['activity_level_id'] ?? null,

                        'health_condition_note' =>
                        $data['health_condition_note'] ?? null,

                        'dietary_restriction_note' =>
                        $data['dietary_restriction_note'] ?? null,

                        'training_location_id' =>
                        $data['training_location_id'] ?? null,

                        'available_days' =>
                        $data['available_days'] ?? null,

                        'trainer_type' =>
                        $data['trainer_type'] ?? null,

                        'disclaimer_accepted' =>
                        $data['disclaimer_accepted'] ?? false,
                    ]);

                    // Multiple health conditions
                    $profile->healthConditions()->sync(
                        $data['health_condition_ids'] ?? []
                    );

                    // Multiple dietary restrictions
                    $profile->dietaryRestrictions()->sync(
                        $data['dietary_restriction_ids'] ?? []
                    );
                }


                // Coach registration

                elseif ($data['role'] === 'coach') {

                    $user->coachProfile()->create([
                        'identity_number' => $data['identity_number'] ?? null,
                        'specialization' => $data['specialization'] ?? null,
                        'experience' => $data['experience'] ?? null,
                        'location' => $data['location'] ?? null,
                        'certification' => $data['certification'] ?? null,
                        'birth_year' => $data['birth_year'] ?? null,
                        'price' => $data['price'] ?? null,
                    ]);
                }

                $token = $user->createToken('auth_token')->plainTextToken;

                return [
                    'user' => $user,
                    'token' => $token,
                ];
            });
        } catch (\Throwable $e) {

            Log::error('User registration failed', [
                'email' => $data['email'] ?? null,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
    public function login(array $data)
    {
        try {
            $user = $this->userRepository->findByEmail($data['email']);

            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid email or password'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Throwable $e) {
            Log::error('User login failed', [
                'email' => $data['email'] ?? null,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    // login with gogle email
    public function loginWithGoogle(string $idToken)
    {
        try {
            // Google token verification will be added here
            $client = new Client([
                'client_id' => config('services.google.client_id'),
            ]);
            $payload = $client->verifyIdToken($idToken);
            if (!$payload) {
                throw ValidationException::withMessages([
                    'google' => ['Invalid Google token'],
                ]);
            }
            $email = $payload['email'] ?? null;
            if (!$email) {
                throw ValidationException::withMessages([
                    'email' => ['Email not found in Google account'],
                ]);
            }
            $user = $this->userRepository->findByEmail($email);

            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => ['User not found'],
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('User login with Google failed', [
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
    public function logout($user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
