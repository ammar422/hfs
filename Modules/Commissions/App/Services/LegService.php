<?php

namespace Modules\Commissions\App\Services;

use Modules\Users\App\Models\User;


class LegService
{
    public function findLastNode(User $sponsor, string $legType): User
    {
        $current = $sponsor;
        while (true) {
            $next = $current->{$legType . '_leg'};
            if (!$next) break;
            $current = $next;
        }
        return $current;
    }
}
