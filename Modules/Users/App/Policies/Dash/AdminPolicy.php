<?php

namespace Modules\Users\App\Policies\Dash;

use Dash\Policies\Policy;

class AdminPolicy extends Policy
{
    /**
     * @var string
     */
    protected $resource = 'Admins';
}
