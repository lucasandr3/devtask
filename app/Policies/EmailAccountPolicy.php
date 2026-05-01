<?php

namespace App\Policies;

use App\Models\EmailAccount;
use App\Models\User;

class EmailAccountPolicy
{
    public function view(User $user, EmailAccount $emailAccount): bool
    {
        return $user->id === $emailAccount->user_id;
    }

    public function update(User $user, EmailAccount $emailAccount): bool
    {
        return $user->id === $emailAccount->user_id;
    }

    public function delete(User $user, EmailAccount $emailAccount): bool
    {
        return $user->id === $emailAccount->user_id;
    }
}
