<?php

namespace Modules\Users\App\Policies\Dash;

use Dash\Policies\Policy;

class AdminGroupPolicy extends Policy {
	/**
	 * @var string
	 */
	protected $resource = 'AdminGroups';
}

