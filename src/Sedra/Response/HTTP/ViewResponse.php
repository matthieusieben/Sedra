<?php


namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP\HTML;
use Sedra\View;

/**
*
*/
class ViewResponse extends HTML
{
	public $view;

	function __construct(Request &$request, View $view, $status = 200)
	{
		parent::__construct($request, null, $status);
		$this->view = $view;
	}

	function body()
	{
		$this->body = $this->body ?: $this->view->render();
		return $this->body;
	}
}