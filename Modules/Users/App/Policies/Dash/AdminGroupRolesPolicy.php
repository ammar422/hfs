<?php

namespace Modules\Users\App\Policies\Dash;

use Dash\Policies\Policy;

class AdminGroupRolesPolicy extends Policy {
	/**
	 * @var string
	 */
	protected $resource = 'AdminGroupRoles';
}

