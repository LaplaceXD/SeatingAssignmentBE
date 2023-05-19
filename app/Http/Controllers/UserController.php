<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserDetailsRequest;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index()
    {
        return User::where('IsActive', true)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCreateRequest $request)
    {
        return User::create($request->safe()->except('ConfirmPassword'));
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
    public function update_details(UserDetailsRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user || !$user->IsActive) return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);

        $user->update($request->safe()->all());
        return $user;
    }

    public function change_password(ChangePasswordRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user || !$user->IsActive) return response(['message' => 'User not found.'], Response::HTTP_NOT_FOUND);

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
