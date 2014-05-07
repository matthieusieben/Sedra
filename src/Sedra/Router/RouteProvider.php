<?php

namespace Sedra\Router;

interface RouteProvider {
	public function get_routes();
	public function get_route($route_name);
	public function get_route_names();
}