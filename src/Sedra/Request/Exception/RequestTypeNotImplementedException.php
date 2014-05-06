<?php

namespace Sedra\Request\Exception;

use Sedra\Request;
use Sedra\Exception;

class RequestTypeNotImplementedException extends Exception
{
	protected $default_message = 'The request method used is not implemented.';
}