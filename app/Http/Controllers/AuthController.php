<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\UpdateMeRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['personal_number', 'password']);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout(LogoutRequest $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([], 204);
    }

    public function update(UpdateMeRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (empty($data['password'] ?? null)) {
            unset($data['password']);
        }

        $user->fill($data);
        $user->save();

        return response()->json([
            'user' => $user->fresh(),
        ]);
    }
}
