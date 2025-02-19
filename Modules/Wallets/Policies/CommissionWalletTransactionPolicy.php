<?php

namespace Modules\Wallets\Policies;

use Modules\Users\App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Wallets\Entities\CommissionWalletTransaction;

class CommissionWalletTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CommissionWalletTransaction $CommissionWalletTransaction): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CommissionWalletTransaction $CommissionWalletTransaction): bool
    {
        return $user->id == $CommissionWalletTransaction->user_id && $CommissionWalletTransaction->status == 'pending';
    }

    public function delete(User $user, CommissionWalletTransaction $CommissionWalletTransaction): bool
    {
        // return false;
        return $user->id == $CommissionWalletTransaction->user_id && $CommissionWalletTransaction->status == 'pending';

    }
}
