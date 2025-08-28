<?php

namespace App\Policies\V1;

use App\Http\Permissions\Abilities;
use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(User $user) {
        return $user->tokenCan(Abilities::CreateTicket) || $user->tokenCan(Abilities::CreateOwnTicket);
    }

    /**
     * Determine if the given post can be updated by the user.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }

    public function replace(User $user) {
        return $user->tokenCan(Abilities::ReplaceTicket);
    }

    public function destroy(User $user, Ticket $ticket) {
        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }
}
