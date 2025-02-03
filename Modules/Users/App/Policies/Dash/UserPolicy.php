<?php

namespace Modules\Users\App\Policies\Dash;

use Dash\Policies\Policy;

class UserPolicy extends Policy
{
    /**
     * @var string
     */
    protected $resource = 'Users';
}
