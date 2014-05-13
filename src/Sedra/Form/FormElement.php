<?php

namespace Sedra\Form;

use Sedra\Form;
use Sedra\Form\Exception;

use Sedra\Request;
use Sedra\Request\HTTP as HTTPRequest;

use Sedra\View;
use Sedra\View\Viewable;

abstract class FormElement implements Viewable {

	protected $form;
	protected $view_name;

	public function set_form(Form $form)
	{
		if ($this->form && $this->form !== $form) {
			throw new Exception('A field can only be used in one form.');
		}

		$this->form = $form;
	}

	abstract public function parse(array $data, array $files = array());
	abstract public function is_valid();
	abstract public function errors();
	abstract public function values();
	abstract public function reset();

	protected function get_view_name()
	{
		return $this->view_name;
	}

	protected function get_view_data()
	{
		return (array) $this;
	}

	public function get_view()
	{
		return View::factory($this->get_view_name(), $this->get_view_data());
	}

	public function __tostring()
	{
		return (string) $this->get_view();
	}
}