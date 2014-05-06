<?php

namespace Sedra\Database;

/**
*
*/
class Model
{
	protected $name;
	protected $schema;

	function __construct($name, $schema)
	{
		$this->name = $name;
		$this->schema = $schema;
	}

	public function __get($name)
	{
		return $this->$name;
	}

	public function ensure()
	{
		# XXX
	}

	public function install()
	{

	}

	protected function get_conditions($conditions)
	{
		if (!is_array($conditions)) {
			$conditions = array(
				$this->schema['primary_key'] => $conditions,
			);
		}
		return $conditions;
	}

	public function find($conditions)
	{
		$conditions = $this->get_conditions($conditions);
		# code...
	}

	public function insert($item)
	{
		# code...
	}

	public function delete($id)
	{
		$conditions = $this->get_conditions($id);
		# code...
	}

	public function get_insert_form()
	{
		# XXX
	}

	public function get_update_form($item)
	{
		# XXX
	}

	public function get_delete_form($item)
	{
		# code...
	}
}