<?php

namespace Modules\Commissions\App\Services;

use Modules\Users\App\Models\User;

class LegService
{
    public function findLastNode(User $user, string $legType): User
    {
        $current = $user;
        while ($current->{"{$legType}_leg_id"}) {
            $current = User::find($current->{"{$legType}_leg_id"});
        }
        return $current;
    }

    public function getLegCV(User $user, string $legType): float
    {
        $total = 0;
        $current = $user->{"{$legType}_leg_id"} ? User::find($user->{"{$legType}_leg_id"}) : null;

        while ($current) {
            $total += $current->cv;
            $current = $current->{"{$legType}_leg_id"} ? User::find($current->{"{$legType}_leg_id"}) : null;
        }

        return $total;
    }
}
