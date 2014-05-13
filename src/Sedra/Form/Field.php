<?php
namespace Sedra\Form;

use Sedra\Form;
use Sedra\Form\Exception;
use Sedra\Form\FormElement;

use Sedra\Locale;

use Sedra\Request\HTTP as HTTPRequest;


abstract class Field extends FormElement {

	public $name;
	public $value;
	public $default = null;
	public $required = false;
	public $label;

	protected $valid = null;
	protected $errors = array();

	function __construct($name, array $options = array())
	{
		if (!$name) {
			throw new Exception('Every form field should have a name.');
		}

		$this->name = $name;

		$options += array(
			'label' => $name
		);

		foreach ($options as $key => $value) {
			$this->$key = $value;
		}

		if (!isset($this->value)) {
			$this->value = $this->default;
		}
	}

	public function parse(array $data, array $files = array())
	{
		$this->value = @$data[$this->name];
	}

	public function validate()
	{
		if (isset($this->valid))
			return $this->valid;

		if ($this->required && !isset($this->value))
			$this->errors[] = Locale::t('The field @name is required.', array('@name' => $this->label));

		return $this->valid = empty($this->errors);
	}

	public function is_valid()
	{
		if(!isset($this->valid))
			$this->validate();

		return $this->valid;
	}

	public function errors()
	{
		if(!isset($this->valid))
			$this->validate();

		return $this->errors;
	}

	public function reset()
	{
		$this->value = $this->default;
		$this->valid = null;
		$this->errors = array();
	}

	public function values()
	{
		if ($this->name[0] !== '_')
			return array($this->name => $this->value);
	}
}