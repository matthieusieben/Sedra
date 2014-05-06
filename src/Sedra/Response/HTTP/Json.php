<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP;

/**
*
*/
class Json extends HTTP
{
	public $data;

	function __construct(Request &$request, array $data, $status = 200)
	{
		parent::__construct($request, $status);

		$this->data = $data;

		# XXX
		$this->headers['content-type'] = 'text/json';
	}

	public function body()
	{
		return $this->body = json_encode($this->data);
	}
}