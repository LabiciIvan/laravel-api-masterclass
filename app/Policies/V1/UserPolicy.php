<?php

namespace App\Policies\V1;

use App\Http\Permissions\Abilities;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(User $user)
    {
 
        return $user->tokenCan(Abilities::CreateUser);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user)
    {
        return $user->tokenCan(Abilities::UpdateUser);
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(User $user)
    {
        return $user->tokenCan(Abilities::ReplaceUser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return $user->tokenCan(Abilities::DeleteUser);
    }
}
