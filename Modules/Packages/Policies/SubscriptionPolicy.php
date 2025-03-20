<?php

namespace Modules\Packages\Policies;

use Modules\Users\App\Models\User;
use Modules\Packages\Entities\Subscription;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscriptionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Subscription $Subscription): bool
    {
        return $Subscription->user_id ==  $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Subscription $Subscription): bool
    {
        return false;
    }

    public function delete(User $user, Subscription $Subscription): bool
    {
        return $Subscription->user_id ==  $user->id;
    }
}
