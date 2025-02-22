<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResertPasswordRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends Controller implements HasMiddleware
{
    public function __construct(private readonly AuthService $service)
    {
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only("email", "password");
        return ResponseHelper::response($this->service->login($credentials));

    }

    public function register(RegisterRequest $request)
    {

        return ResponseHelper::response($this->service->register($request->all()));
    }

    public function profile()
    {

        return ResponseHelper::response($this->service->profile());

    }

    public function updateProfile(ProfileRequest $request)
    {

        return ResponseHelper::response($this->service->updateProfile($request->all()));

    }

    public function logout()
    {

        return ResponseHelper::response($this->service->logout());

    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {

        return ResponseHelper::response($this->service->forgotPassword());

    }

    public function resetPassword(ResertPasswordRequest $request)
    {

        $attributes = [
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
            'pin' => $request->input('pin')
        ];
        return ResponseHelper::response($this->service->resetPassword($attributes));

    }

    public function verifyAccount(Request $request)
    {
        $request->validate([
            'pin' => 'required|numeric|min_digits:4',
            'email' => ['required', 'email'],
        ]);

        $attributes = [
            'pin' => $request->input('pin'),
            'email' => $request->input('email'),
        ];
        return ResponseHelper::response($this->service->verifyAccount($attributes));

    }

    public function sendNewPin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $attributes = [
            'email' => $request->input('email'),
        ];
        return ResponseHelper::response($this->service->sendNewPin($attributes));

    }


    public function updatePassword(Request $request)
    {

        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->numbers()
                    ->symbols()
            ],
        ]);
        $attributes = [
            'password' => $request->input('password'),
        ];
        return ResponseHelper::response($this->service->updateProfile($attributes));


    }

    public function deleteMyAccount()
    {
        return ResponseHelper::response($this->service->deleteMyAccount());
    }

    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'verified'], except: ['login', 'register', 'resetPassword', 'forgotPassword', 'verifyAccount', 'sendNewPin']),
        ];
    }
}
