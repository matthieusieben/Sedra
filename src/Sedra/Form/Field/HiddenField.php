<?php

namespace Sedra\Form\Field;

use Sedra\Form\Field;

class HiddenField extends Field {
	public $type = 'hidden';
	protected $view_name = 'form/input';
}