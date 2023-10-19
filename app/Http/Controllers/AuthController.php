<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required', 'confirmed', Password::min(6)
            ]
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'accesses' => []

        ]);

        $token = $user->createToken('main')->plainTextToken;
        return response([
            'user' => $user, 'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required'
            ],
            'remember' => 'boolean'
        ]);
        $remember = $data['remember'] ?? false;
        unset($data['remember']);

        if (!Auth::attempt($data, $remember)) {
            return response(['error' => 'Wrong email or password'], 422);
        }
        /** @var \App\Models\User $user **/
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        return new JsonResponse([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        return response([
            'success' => true
        ]);
    }
}
