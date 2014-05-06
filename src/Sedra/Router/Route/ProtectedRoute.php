<?php

namespace Sedra\Router\Route;

use Sedra\Router\Route;
use Sedra\Request;

/**
*
*/
class ProtectedRoute extends Route
{
	protected $allowed_roles = array(USER_ROLE);

	function __construct($vars)
	{
		parent::__construct($vars);
	}

	public function access(Request &$request)
	{
		if(!parent::access($request))
			return false;

		$user = $request->get_user();

		return $user && in_array($user->role, $this->allowed_roles);
	}
}