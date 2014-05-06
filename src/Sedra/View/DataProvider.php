<?php

namespace Sedra\View;

use Sedra\Request;
use Sedra\View;

/**
 *
 **/
interface DataProvider
{
	public function get_view_data($name, array &$data, Request &$request);
}