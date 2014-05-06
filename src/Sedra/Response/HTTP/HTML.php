<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP;

/**
*
*/
class HTML extends HTTP
{
	function __construct(Request &$request, $html, $status = 200)
	{
		parent::__construct($request, $status);

		$this->body = $html;
		$this->status = $status;
		$this->headers['content-type'] = 'text/html';
	}
}