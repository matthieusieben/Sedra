<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;

/**
*
*/
class Dumper extends Page
{
	function __construct(Request &$request, $content, $status = 200)
	{
		ob_start();
		var_dump($content);
		$dumped = ob_get_clean();

		parent::__construct($request, $dumped, $status);
	}
}