<?php

namespace Modules\Wallets\Policies;

use Modules\Users\App\Models\User;
use Modules\Wallets\Entities\CommissionWallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CommissionWallet $CommissionWallet): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return $user->user_type == 'charity';
    }

    public function update(User $user, CommissionWallet $CommissionWallet): bool
    {
        return false;
    }

    public function delete(User $user, CommissionWallet $CommissionWallet): bool
    {
        return false;
    }
}
