<?php

namespace Modules\Packages\Policies;

use Modules\Users\App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Packages\Entities\Package;

class PackagePolicy
{
    use HandlesAuthorization;



    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Package $Package): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Package $Package): bool
    {
        return false;
    }

    public function delete(User $user, Package $Package): bool
    {
        return false;
    }
}
