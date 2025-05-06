<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\LoginRequest;
use App\Http\Requests\Api\Admin\RegisterRequest;
use App\Services\AdminAuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $adminAuthService;
    public function __construct(AdminAuthService $adminAuthService)
    {
        $this->adminAuthService = $adminAuthService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->adminAuthService->register($request);
    }

    public function login(LoginRequest $request)
    {
        return $this->adminAuthService->login($request);
    }

    public function logout()
    {
        return $this->adminAuthService->logout();
    }
}
