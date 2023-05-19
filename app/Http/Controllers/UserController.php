<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
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
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDetails(UserDetailsRequest $request, string $id)
    {
        $user = User::find($id);
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        $user->update($request->safe()->all());
        return $user;
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {
        $fields = $request->validated();

        $user = User::find($id);
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        if (!Hash::check($fields['OldPassword'], $user->Password)) {
            throw ValidationException::withMessages(['OldPassword' => 'Password incorrect.']);
        }

        $user->update($request->safe()->only(['Password']));
        return ['message' => 'Password changed successfully.'];
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
