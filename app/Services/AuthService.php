<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private UserRepositoryInterface $userRepository) {}
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        $user = $this->userRepository->create($data);

        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
    public function login(array $data)
    {
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
    }
    public function logout($user):void{
        $user->currentAccessToken()?->delete();
    }
}
