<?php

namespace HXPHP\System\Configs;

class Config
{
	public $global;

	public function __construct()
	{
		$this->global = new GlobalConfig;
	}

	public function addEnv($environment)
	{
		$defineEnvironment = new DefineEnvironment;
		$defineEnvironment->setEnv($environment);
	}
}