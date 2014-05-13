<?php

namespace Sedra\Request;

use Sedra\Response;
use Sedra\Request;

/**
 *
 **/
class Cache
{
	protected $disabled = false;
	protected $request;
	protected $dependences;
	protected $validity = -1;

	function __construct(Request &$request)
	{
		$this->request =& $request;

		switch ($request->method) {
		case 'PUT':
		case 'POST':
		case 'DELETE':
			$this->disable();

		default:
			$this->dependences = array(
				'method' => $request->method,
				'query' => $request->query,
			);
			break;
		}
	}

	public function validity($time)
	{
		if ($time === 0) {
			$this->disable();
			return $this->validity = 0;
		} elseif($time <= 1) {
			return $this->validity = 1;
		} elseif ($this->validity === -1) {
			return $this->validity = $time;
		} elseif ($time < $this->validity) {
			return $this->validity = $time;
		} else {
			return $this->validity;
		}
	}

	public function disable()
	{
		$this->disabled = true;
	}

	public function disabled()
	{
		return $this->disabled;
	}

	public function depends_on($key)
	{
		switch ($key) {
		case 'user':
			$this->dependences['user'] = $this->request->user->id;
			break;
		case 'role':
			$this->dependences['role'] = $this->request->user->role;
			break;
		case 'locale':
			$this->dependences['locale'] = $this->request->user->locale;
			break;
		}
	}

	public function set(Response &$response)
	{
		if (!$this->disabled) {
			# TODO store response in cache
		}
	}

	public function get()
	{
		# TODO
		return null;
	}
}