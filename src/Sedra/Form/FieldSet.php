<?php

namespace Sedra\Form;

use Sedra\Form;
use Sedra\Form\FormElement;

use Sedra\Request;
use Sedra\Request\HTTP as HTTPRequest;

use Sedra\View\Viewable;

/**
 *
 **/
class FieldSet extends FormElement {

	public $fields;

	protected $valid;
	protected $view_name = 'form/set';

	function __construct(array $fields = array())
	{
		$this->fields = $fields;
	}

	public function set_form(Form &$form)
	{
		parent::set_form($form);
		foreach ($this->fields as &$field)
			$field->set_form($form);
	}

	public function add(FormElement $field)
	{
		$field->set_form($this->form);
		$this->fields[] = $field;
	}

	public function parse(array &$data, array &$files = array())
	{
		foreach ($this->fields as &$field)
			$field->parse($data, $files);
	}

	public function is_valid()
	{
		if (isset($this->valid))
			return $this->valid;

		$this->valid = true;

		foreach ($this->fields as &$field)
			if (!$field->is_valid())
				$this->valid = false;

		return $this->valid;
	}

	public function errors()
	{
		$errors = array();
		foreach ($this->fields as &$field)
			$errors += (array) $field->errors();

		return $errors;
	}

	public function values()
	{
		$values = array();
		foreach ($this->fields as &$field)
			$values += (array) $field->values();

		return $values;
	}

	public function reset()
	{
		foreach ($this->fields as &$field)
			$field->reset();
	}
}