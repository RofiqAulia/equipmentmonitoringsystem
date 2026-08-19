<?php

namespace App\Policies;

use App\Models\RetrievalLog;
use App\Models\User;

class RetrievalLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models (perform retrieval).
     */
    public function create(User $user): bool
    {
        return Auth::check() || $user !== null;
    }

    /**
     * Determine whether the user can delete or update logs.
     */
    public function delete(User $user, RetrievalLog $retrievalLog): bool
    {
        return $user->isAdmin();
    }
}
