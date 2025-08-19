<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Policies\V1\UserPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{

    protected $policy = UserPolicy::class;
    
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
    public function store(StoreUserRequest $request)
    {
        try {
            Gate::authorize('create', User::class);
            return new UserResource(User::create($request->mappedAttributes()));
        } catch (AuthorizationException $ex) {
            return $this->error('Not authorized to create a new user', 401);
        }
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
    public function update(UpdateUserRequest $request, string $id)
    {
        try {
            Gate::authorize('update', User::class);

            $user = User::where('id', $id)->firstOrFail();

            $user->update($request->mappedAttributes());

            return new UserResource($user);

        } catch (ModelNotFoundException $ex) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('Not authorized to update a user', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceUserRequest $request, string $id)
    {
        try {
            Gate::authorize('replace', User::class);

            $user = User::where('id', $id)->firstOrFail();

            $user->update($request->mappedAttributes());

            return new UserResource($user);
        } catch (ModelNotFoundException $ex) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('Not authorized to replace a user', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Gate::authorize('destroy', User::class);

            $user = User::where('id', $id)->firstOrFail();

            $user->delete();

            return $this->ok('User successfuly has been deleted');
        } catch (ModelNotFoundException $ex) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('Not authorized to delete a user', 401);
        }
    }
}
