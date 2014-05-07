<?php

namespace Sedra\Controller;

use Sedra\Controller;

use Sedra\Database\Model;
use Sedra\Database\ModelProvider;

use Sedra\Form;
use Sedra\Form\FieldSet;
use Sedra\Form\Field\InputField;

use Sedra\Request\HTTP as Request;

use Sedra\Router;
use Sedra\Router\Route;
use Sedra\Router\RouteProvider;

use Sedra\View;

/**
*
*/
class Sandbox extends Controller implements RouteProvider, ModelProvider
{
	private function get_form()
	{
		return new Form('SandboxTestResult', array(
			new FieldSet(array(
				new InputField('name', array('default' => 'Your name')),
			)),
		), 'POST');
	}

	public function test(Request $request) {
		$form = $this->get_form();

		if ($form->process($request)) {
			return $form->values();
		}
		else {
			return $form;
		}
	}

	public function &get_routes() {
		$this->routes = $this->routes ?: array(
			Route::factory(array(
				'name' => 'SandboxTest',
				'methods' => array('GET'),
				'query' => 'test',
				'controller' => array(&$this, 'test'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			)),
			Route::factory(array(
				'name' => 'SandboxTestResult',
				'methods' => array('POST'),
				'query' => 'test',
				'controller' => array(&$this, 'test'),
				'response_wrapper' => '\Sedra\Response\HTTP\Dumper',
			)),
		);
		return $this->routes;
	}

	public function get_model_names()
	{
		return array();
	}

	public function get_model($model_name)
	{
		if (isset($this->models[$model_name]))
			return $this->models[$model_name];
		switch ($model_name) {
		default:
			return $this->models[$model_name] = null;
		}
	}
}