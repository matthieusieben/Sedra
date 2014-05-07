<?php

namespace Sedra\Database;

interface ModelProvider
{
	public function get_model($name);
	public function get_models();
	public function get_model_names();
}