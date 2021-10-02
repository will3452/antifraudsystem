<?php

namespace App\Policies;

use App\Models\Fee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('view any fee');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Fee $fee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Fee $fee)
    {
        return $user->can('view fee details');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('create fee');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Fee $fee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Fee $fee)
    {
        return $user->can('update fee');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Fee $fee
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Fee $fee)
    {
        return $user->can('delete fee');
    }
}
