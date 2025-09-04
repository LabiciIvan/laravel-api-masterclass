<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\TicketResource;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        Gate::authorize('store', Ticket::class);

        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show(int $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->include('user')) {
                return new TicketResource($ticket->load('authors'));
            }

            return new TicketResource($ticket);

        }  catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found.', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, int $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // Authorizaton using Gate facade
            Gate::authorize('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        }  catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found.', 404);
        } catch(AuthorizationException $ex) {
            return $this->error('You are not authorized to update this resource.', 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, int $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            // Authorizaton using Gate facade
            Gate::authorize('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        }  catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found.', 404);
        } catch(AuthorizationException $ex) {
            return $this->error('You don\'t have access to update this resource.', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            Gate::authorize('destroy', $ticket);

            $ticket->delete();
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', 404);
        } catch(AuthorizationException $ex) {
            return $this->error('You don\'t have access to update this resource.', 401);
        }

        return $this->ok('Ticket deleted');
    }

}
