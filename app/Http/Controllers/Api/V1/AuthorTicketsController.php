<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;

class AuthorTicketsController extends ApiController
{
    public function index(int $author_id, TicketFilter $filters) {
        return TicketResource::collection(Ticket::where('user_id', $author_id)->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, int $author_id)
    {
        try {
            Gate::authorize('store', Ticket::class);

            return new TicketResource(Ticket::create($request->mappedAttributes(['author' => 'user_id'])));
        } catch (AuthorizationException $ex) {
            $this->error('You are not authorized to create this resource', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, int $author_id, int $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)->where('user_id', $author_id)->firstOrFail();

            Gate::authorize('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        }  catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            $this->error('You are not authorized to patch this resource', 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, int $author_id, int $ticket_id)
    {
        try {

            $ticket = Ticket::where('id', $ticket_id)->where('user_id', $author_id)->firstOrFail();

            Gate::authorize('replace', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        }  catch (ModelNotFoundException $e) {
            return $this->error('Ticket cannot be found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to put this resource', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $author_id, int $ticket_id)
    {
        try {
            $ticket = Ticket::where('id', $ticket_id)->where('user_id', $author_id)->firstOrFail();

            Gate::authorize('delete', $ticket);

            $ticket->delete();
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', 404);
        } catch (AuthorizationException $ex) {
            return $this->error('You are not authorized to delete this resource', 403);
        }

        return $this->ok('Ticket deleted');
    }
}
