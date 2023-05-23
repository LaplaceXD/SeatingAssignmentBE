<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Http\Requests\UserDetailsRequest;
use App\Http\Requests\ChangePasswordRequest;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('Role');

        return User::active()
            ->ofRole(UserRole::tryFrom($role))
            ->orderByDesc('CreatedAt')
            ->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort_unless($user->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDetails(UserDetailsRequest $request, User $user)
    {
        abort_unless($user->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        $user->update($request->safe()->all());
        return $user->refresh();
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        abort_unless($user->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');

        $fields = $request->validated();
        if (!Hash::check($fields['OldPassword'], $user->Password)) {
            throw ValidationException::withMessages(['OldPassword' => 'Password incorrect.']);
        }

        $user->update($request->safe()->only(['Password']));
        return ['message' => 'Password changed successfully.'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->IsActive = false;
        $user->save();

        return ['message' => 'User deleted successfully.'];
    }
}
