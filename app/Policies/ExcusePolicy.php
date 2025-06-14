<?php

namespace App\Policies;

use App\Models\Excuse;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExcusePolicy
{
    use HandlesAuthorization;

    public function approve(User $user, Excuse $excuse)
    {
        // Only the task owner can approve excuses
        return $user->id === $excuse->task->user_id;
    }
}
