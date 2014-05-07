<?php

namespace Sedra\Request;

use Sedra\Request;

class HTTP extends Request
{
	public $get;
	public $post;
	public $files;
	public $is_ajax;

	public $data;

	function __construct()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		$query = @$_SERVER['PATH_INFO'] ?: @$_GET['q'];

		if ($query) {
			$query = strtr(trim($query, '/'), '//', '/');
		}

		if (empty($query)) {
			$query = 'index';
		}

		parent::__construct($method, $query);

		$this->get =& $_GET;
		$this->post =& $_POST;
		$this->files =& $_FILES;
		$this->is_ajax = @$_SERVER['HTTP_X_REQUESTED_WITH'] || @$_REQUEST['__ajax'];

		if ($method === 'GET') {
			$this->data =& $this->get;
		} else {
			$this->data =& $this->post;
		}
	}

	public function get_user()
	{
		if(isset($this->user))
			return $this->user;

		# TODO

		return $this->user;
	}
}