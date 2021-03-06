<?php

namespace Sedra;

use Sedra\Request\HTTP as HTTPRequest;

use Sedra\Form\FormElement;
use Sedra\Form\FieldSet;
use Sedra\Form\Field\HiddenField;
use Sedra\Form\Exception;

/**
 *
 **/
class Form extends FieldSet
{
	public $action;
	public $method;

	protected $id;
	protected $request;
	protected $submitted;
	protected $view_name = 'form/form';

	function __construct($action, array $fields = array(), $method = 'POST')
	{
		$bt = debug_backtrace(false, 1);
		$this->id = sha1($bt[0]['file'].':'.$bt[0]['line']);
		$this->action = $action;
		$this->method = $method;

		parent::__construct($fields);
		$this->set_form($this);
		$this->add(new HiddenField('__fid', array('value' => $this->id)));
	}

	/**
	 * Allow read only access to variables
	 *
	 * @var name	property name
	 **/
	public function __get($name)
	{
		if (property_exists($this, $name))
			return $this->$name;
	}

	public function process(HTTPRequest $request)
	{
		if (isset($this->submitted))
			return $this->submitted;

		$this->request = $request;
		$this->submitted = isset($request->data['__fid']) && $this->id === $request->data['__fid'];

		if($this->submitted) {
			$this->parse($request->data, $request->files);
		}

		return $this->submitted;
	}

	public function set_form(Form $form) {
		if ($form !== $this)
			throw new Exception('A form cannot be added inside another form.');

		parent::set_form($form);
	}
}
