<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Services\AuthService;
use App\Traits\Responder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    use Responder;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(path: '/api/auth/register', description: 'Register a new user', responses: [
        new OA\Response(response: 201, description: 'User registered successfully')
    ])]
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());
        return $this->success($result, 'User registered successfully', 201);
    }

    #[OA\Post(path: '/api/auth/login', description: 'Login a user', responses: [
        new OA\Response(response: 200, description: 'Logged in successfully'),
        new OA\Response(response: 401, description: 'Invalid credentials')
    ])]
    public function login(LoginUserRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());
        
        if (!$result) {
            return $this->error('Invalid credentials', 401);
        }

        return $this->success($result, 'Logged in successfully');
    }

    #[OA\Post(path: '/api/auth/logout', description: 'Logout the authenticated user', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 200, description: 'Logged out successfully')
    ])]
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->success([], 'Logged out successfully');
    }

    #[OA\Get(path: '/api/auth/me', description: 'Get authenticated user details', security: [['sanctum' => []]], responses: [
        new OA\Response(response: 200, description: 'User retrieved successfully')
    ])]
    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user(), 'User retrieved successfully');
    }
}
