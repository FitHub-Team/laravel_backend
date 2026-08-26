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
        private EmailVerificationService $emailVerificationService
    ) {}
    public function register(array $data)
    {
        try {
             return DB::transaction(function () use ($data) {
            $data['password'] = Hash::make($data['password']);
            // $data['role'] = 'user';
            if (User::where('email', $data['email'])->exists()) {
                throw ValidationException::withMessages([
                    'email' => ['Email already registered'],
                ]);
            }
            $user = $this->userRepository->create($data);
            if ($data['role'] === 'user') {
                $user->userProfile()->create([
                    'gender' => $data['gender'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'height' => $data['height'] ?? null,
                    'weight' => $data['weight'] ?? null,
                    'health_goal' => $data['health_goal'] ?? null,
                    'medical_conditions' => $data['medical_conditions'] ?? null,
                    'allergies' => $data['allergies'] ?? null,
                    'dietary_preference' => $data['dietary_preference'] ?? null,
                ]);
            } elseif ($data['role'] === 'coach') {
                $user->coachProfile()->create([
                    'identity_number' => $data['identity_number'] ?? null,
                    'specialization' => $data['specialization'] ?? null,
                    'experience' => $data['experience'] ?? null,
                    'location' => $data['location'] ?? null,
                    'certification' => $data['certification'] ?? null,
                    'birth_year' => $data['birth_year'] ?? null,
                ]);
            }


            $this->emailVerificationService->sendVerificationCode($user);


            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'user' => $user,
                'token' => $token,
            ];
        }); }catch (\Throwable $e) {
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

            if (! $user || ! Hash::check($data['password'], $user->password)) {
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
            if (! $payload) {
                throw ValidationException::withMessages([
                    'google' => ['Invalid Google token'],
                ]);
            }
            $email = $payload['email'] ?? null;
            if (! $email) {
                throw ValidationException::withMessages([
                    'email' => ['Email not found in Google account'],
                ]);
            }
            $user = $this->userRepository->findByEmail($email);

            if (! $user) {
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
