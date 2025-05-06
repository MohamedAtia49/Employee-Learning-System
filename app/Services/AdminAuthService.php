<?php

namespace App\Services;

use App\Http\Requests\Api\Admin\LoginRequest;
use App\Http\Requests\Api\Admin\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use function Laravel\Prompts\error;

class AdminAuthService
{
    use ApiResponse;
    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],  // Fixed: access array directly
            'email' => $validated['email'],  // Fixed: access array directly
            'password' => Hash::make($validated['password']),  // Fixed: access array directly
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return $this->errorResponse('Could not create token', 500);
        }

        return $this->successResponse([
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse('Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            return $this->errorResponse('Could not create token', 401);
        }

        return $this->successResponse([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            // Check if token exists and is valid
            if (!$token || !JWTAuth::check()) {
                return $this->errorResponse('Already logged out or invalid token', 404);
            }

            JWTAuth::invalidate($token);

        } catch (JWTException $e) {
            return $this->errorResponse('Failed to logout, please try again', 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
            'data' => null,
        ], 200);
    }
}
