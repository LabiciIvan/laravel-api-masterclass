<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UsersController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // We add whereHas to filter users with tickets data, otherwise it will not return
        if ($this->include('tickets')) {
            // return UserResource::collection(User::whereHas('tickets')->with('tickets')->paginate());
            return UserResource::collection(User::with('tickets')->paginate());
        }

        return UserResource::collection(User::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // We add whereHas to filter users with tickets data, otherwise it will not return
        if ($this->include('tickets')) {
            // return UserResource::collection(User::whereHas('tickets')->with('tickets')->paginate());
            return UserResource::collection(User::whereHas('tickets', function ($q) use ($user) { $q->where('user_id', $user->id);})->with('tickets')->paginate());
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
