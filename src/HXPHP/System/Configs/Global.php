<?php

namespace HXPHP\System\Configs;

class Global
{
	public $models;
	public $views;
	public $controllers;
	public $static = array();

	public function __construct()
	{
		$this->models = new stdClass;
		$this->views = new stdClass;
		$this->controllers = new stdClass;

	}
}