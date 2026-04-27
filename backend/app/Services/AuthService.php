<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function register(array $data): array
    {
        try {
            Log::info('Registering new user', ['email' => $data['email']]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? 'field_agent',
            ]);

            event(new UserRegistered($user));

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in AuthService@register', [
                'email' => $data['email'] ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function login(array $credentials): ?array
    {
        try {
            Log::info('User login attempt', ['email' => $credentials['email']]);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                Log::warning('Failed login attempt', ['email' => $credentials['email']]);
                return null;
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('User logged in successfully', ['email' => $user->email]);

            return [
                'user' => $user,
                'token' => $token,
            ];
        } catch (\Exception $e) {
            Log::error('Exception in AuthService@login', [
                'email' => $credentials['email'] ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function logout(User $user): void
    {
        try {
            Log::info('User logged out', ['email' => $user->email]);
            $user->currentAccessToken()->delete();
        } catch (\Exception $e) {
            Log::error('Exception in AuthService@logout', [
                'email' => $user->email ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
