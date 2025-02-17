<?php

namespace Modules\Commissions\Policies;

use Modules\Users\App\Models\User;
use Modules\Commissions\Entities\Commission;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommissionPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Commission $Commission): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        $referral = User::where('placement', 'tank')
            ->where('account_type', 'user')
            ->where('account_status', 'active')
            ->findOrFail(request('referral_id'));
        if ($referral->subscription)
            return true;
        return false;
    }

    public function update(User $user, Commission $Commission): bool
    {
        return false;
    }

    public function delete(User $user, Commission $Commission): bool
    {
        return false;
    }
}
