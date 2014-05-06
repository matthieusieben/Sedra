<?php

namespace Sedra\Response;

use Sedra\Request;
use Sedra\Response;

/**
*
*/
abstract class HTTP extends Response
{
	public $request;
	public $status = 200;
	public $headers = array();
	public $body = null;

	function __construct(Request &$request, $status = 200)
	{
		$this->request =& $request;
		$this->status = $status;
	}

	public function send()
	{
		$this->send_status();
		$this->send_headers();
		$this->send_body();
		exit;
	}

	public function body()
	{
		return $this->body;
	}

	public function send_body()
	{
		echo $this->body();
	}

	public function headers()
	{
		return $this->headers;
	}

	public function send_headers()
	{
		foreach ($this->headers() as $key => $value) {
			header("$key: $value");
		}
	}

	public function status()
	{
		return $this->status;
	}

	public function send_status()
	{
		static $stati = array(
			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoritative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',

			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Found',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			307	=> 'Temporary Redirect',

			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			403	=> 'Forbidden',
			404	=> 'Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Timeout',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Long',
			415	=> 'Unsupported Media Type',
			416	=> 'Requested Range Not Satisfiable',
			417	=> 'Expectation Failed',
			418 => 'I\'m a teapot',

			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Bad Gateway',
			503	=> 'Service Unavailable',
			504	=> 'Gateway Timeout',
			505	=> 'HTTP Version Not Supported'
		);

		$text = $stati[$this->status];

		if (substr(php_sapi_name(), 0, 3) === 'cgi') {
			header("Status: {$this->status} {$text}");
		} else {
			$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : false;

			if ( substr($server_protocol, 0, 4) === 'HTTP' ) {
				header($server_protocol." {$this->status} {$text}", true, $this->status);
			} else {
				header("HTTP/1.1 {$this->status} {$text}", true, $this->status);
			}
		}
	}
}