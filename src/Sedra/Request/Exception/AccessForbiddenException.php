<?php

namespace Sedra\Request\Exception;

use Sedra\Request;
use Sedra\Exception;

class AccessForbiddenException extends Exception
{
	protected $default_code = 403;
	protected $default_message = 'You cannot access this page.';

	public $request;

	function __construct(Request &$request)
	{
		$this->request = $request;
		parent::__construct();
	}
}