<?php

namespace Sedra;

use Sedra\Database;
use Sedra\Hook;
use Sedra\Router;

/**
 *
 */
abstract class Controller
{
	protected $options;
	protected $default_options = array();

	protected $engines;
	protected $routes;
	protected $models;

	function __construct(array $options = array())
	{
		$this->options = $options + $this->default_options;
	}
}