<?php

namespace HXPHP\System\Configs;

class Global
{
	public $models;
	public $views;
	public $controllers;

	public function __construct()
	{
		$this->models = new stdClass;
		$this->views = new stdClass;
		$this->controllers = new stdClass;

		//Models
		$this->models->directory = APP_PATH . DS . 'models' . DS;

		//Views
		$this->views->directory = APP_PATH . DS . 'views' . DS;
		$this->views->extension = '.phtml';

		//Controller
		$this->controllers->directory = APP_PATH . DS . 'controllers' . DS;
		$this->controllers->notFound = 'Error404';
	}
}
