<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only(["email", "password"]))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & password does not match our record.'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'token' => $user->createToken($user->email)->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::query()->create($request->validated());
        return new UserResource($user);
    }

    public function logout() {}
}
