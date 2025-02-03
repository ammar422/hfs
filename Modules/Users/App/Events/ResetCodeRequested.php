<?php

namespace Modules\Users\App\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Users\App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class ResetCodeRequested
{
    use Dispatchable, SerializesModels;


    /**
     * @param  User $user
     * @
     */
    public function __construct(public User $user) {}
}
