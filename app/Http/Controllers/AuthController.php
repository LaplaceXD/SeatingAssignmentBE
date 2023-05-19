<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserCreateRequest $request)
    {
        return User::create($request->safe()->all());
    }

    public function login(UserLoginRequest $request)
    {
        $fields = $request->validated();
        $user = User::where('Email', $fields['Email'])->first();

        abort_unless(
            $user?->IsActive && Hash::check($fields['Password'], $user->Password),
            Response::HTTP_BAD_REQUEST,
            'Invalid credentials.'
        );

        return [
            'User' => $user,
            'Token' => $user->createToken('secret')->plainTextToken
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return ['message' => 'Logout successful.'];
    }
}
