<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_event');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->can('view_event');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_event');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->can('update_event');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->can('delete_event');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->can('restore_event');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->can('force_delete_event');
    }

    public function export(User $user, Event $event): bool
    {
        // Hanya admin yang boleh export
        return $user->can('export_event');
    }
}
