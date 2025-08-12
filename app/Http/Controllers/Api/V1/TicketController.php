<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Ticket;
// use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\TicketResource;
use Illuminate\Support\Facades\Log;

class TicketController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->include('user')) {
            return TicketResource::collection(Ticket::with('user')->paginate());
        }
        
        return TicketResource::collection(Ticket::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        Log::debug('Fech ticke using relationship: {INCLUDE}', ['INCLUDE' => $this->include('user')]);

        if ($this->include('user')) {
             Log::debug('Loading relationship for user.');
            return TicketResource::collection(Ticket::with('user')->paginate());
        }

        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
