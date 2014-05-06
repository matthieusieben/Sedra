<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP;

/**
*
*/
class Text extends HTTP
{
	function __construct(Request &$request, $text, $status = 200)
	{
		parent::__construct($request, $status);

		$this->body = $text;
		$this->headers['Content-Type'] = 'text/plain';
	}
}