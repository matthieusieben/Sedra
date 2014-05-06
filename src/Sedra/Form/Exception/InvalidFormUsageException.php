<?php

namespace Sedra\Form\Exception;

use Sedra\Exception;

class InvalidFormUsageException extends Exception
{
	protected $default_message = 'Invalid usage of the Sedra Form API.';
}