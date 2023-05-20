<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserDetailsRequest;
use App\Http\Requests\ChangePasswordRequest;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        if (!$this->authorize('admin', User::class)) throw new UnauthorizedException();

        return User::where('IsActive', true)->get();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');
        if (!$this->authorize('ownerOrHigherRole', $user)) throw new UnauthorizedException();

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDetails(UserDetailsRequest $request, string $id)
    {
        $user = User::find($id);
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');
        if (!$this->authorize('ownerOrHigherRole', $user)) throw new UnauthorizedException();

        $user->update($request->safe()->all());
        return $user;
    }

    public function changePassword(ChangePasswordRequest $request, string $id)
    {
        $user = User::find($id);
        abort_unless($user?->IsActive, Response::HTTP_NOT_FOUND, 'User not found.');
        if (!$this->authorize('owner', $user)) throw new UnauthorizedException();

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
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            if (!$this->authorize('higherRole', $user)) throw new UnauthorizedException();

            $user->IsActive = false;
            $user->save();
        }

        return ['message' => 'User deleted successfully.'];
    }
}
