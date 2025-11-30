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


    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="Se connceter",
     *     description="Se connecter a son compte",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Utilisateur connecter",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string"),
     *         )
     *     ),
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="Register a new user",
     *     description="Creates a new user account",
     *     tags={"Auth"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret1234")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="profile", type="object", nullable=true),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01T12:00:00Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = User::query()->create($request->validated());
        return new UserResource($user);
    }

    public function logout() {}
}
