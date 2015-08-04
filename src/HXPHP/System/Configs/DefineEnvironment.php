<?php

namespace HXPHP\System\Configs;

class DefineEnvironment
{
	public $environment;
	private $currentEnviroment;

	public function __construct()
	{
		$server_name = $_SERVER['SERVER_NAME'];
		$development = new Environments\EnvironmentDevelopment;
	
		if (in_array($server_name, $development->servers)) {
			$this->environment = 'development';
		}
		else {
			$this->environment = 'production';
		}
		$this->currentEnviroment = $this->environment;

		return $this->environment;
	}

	public function setDefaultEnv($environment)
	{
		$env = new Environment;
		if ( is_object($env->add($environment)) )
			$this->currentEnviroment = $environment;
	}

	public function getDefault()
	{
		return $this->currentEnviroment;
	}
}