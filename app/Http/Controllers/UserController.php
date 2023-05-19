<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserDetailsRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        return User::where('IsActive', true)->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        if (!$user || !$user->IsActive) return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDetails(UserDetailsRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user || !$user->IsActive) return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);

        $user->update($request->safe()->all());
        return $user;
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {
        $fields = $request->validated();

        $user = User::find($id);
        if (!$user || !$user->IsActive) return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);

        if (!Hash::check($fields['OldPassword'], $user->Password)) throw ValidationException::withMessages(['OldPassword' => 'Password incorrect.']);

        $user->update($request->safe()->only(['Password']));
        return response(['message' => 'Password changed successfully.'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->IsActive = false;
            $user->save();
        }

        return $id;
    }
}
