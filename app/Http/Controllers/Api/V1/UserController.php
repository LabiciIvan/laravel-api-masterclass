<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\UserResource;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Policies\V1\UserPolicy;
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
        Gate::authorize('create', User::class);

        $user = User::create($request->mappedAttributes());

        // UserRegistered::dispatch($user);
        event(new UserRegistered($user));

        return new UserResource($user);
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
            return $this->error('User not found', config('responses.http.codes.not_found'));
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
            return $this->error('User not found', config('responses.http.codes.not_found'));
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
            return $this->error('User not found', config('responses.http.codes.not_found'));
        }
    }
}
