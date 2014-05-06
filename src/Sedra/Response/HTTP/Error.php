<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP as HTTPResponse;
use Sedra\View;
use Sedra\View\Viewable;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class Error extends HTTPResponse
{
	public $exception;
	private $view;

	function __construct(Request &$request, \Exception $exception)
	{
		$this->exception = $exception;

		try {

			if ($exception instanceof Viewable) {
				$exception_view = $exception->get_view();
			}
			else {
				$exception_view = View::factory('exception', array('exception' => $exception));
			}

			$this->view = View::factory('page', array('content' => $exception_view));

			if ($exception instanceof \Sedra\Exception) {
				$status = $exception->getCode();
			} else {
				$status = 500;
			}

			$this->headers['content-type'] = 'text/html';

		} catch (\Exception $e) {
			$this->body = 'Error ' . $e->getCode() . "\r\n\r\n" .  $e->getMessage();
			$status = 500;
			$this->headers['content-type'] = 'text/plain';
		}

		parent::__construct($request, $status);
	}

	function body()
	{
		$this->body = $this->body ?: $this->view->render();
		return $this->body;
	}
}