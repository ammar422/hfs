<?php

namespace Modules\Ranks\Policies;

use Modules\Users\App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Ranks\Entities\Rank;

class RanksPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Rank $Rank): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Rank $Rank): bool
    {
        return false;
    }

    public function delete(User $user, Rank $Rank): bool
    {
        return false;
    }
}
