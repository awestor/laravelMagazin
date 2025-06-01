<?php
namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\AuthRequest;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(protected readonly AuthService $authService)
    {    }

    public function showLogin(): View
    {
        return view('auth.login', $this->authService->getAssets('login'));
    }

    public function showRegisterForm(): View
    {
        return view('auth.register', $this->authService->getAssets('register'));
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request->validated());
    }

    public function register(AuthRequest $request)
    {
        return $this->authService->register($request->validated());
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
