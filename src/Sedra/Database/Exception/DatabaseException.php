<?php

namespace Sedra\Database\Exception;

use Sedra\Exception;

class DatabaseException extends Exception {
	protected $default_message = 'A database related error occurred.';
}