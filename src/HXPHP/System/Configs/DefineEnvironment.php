<?php

namespace HXPHP\System\Configs;

class DefineEnvironment
{
	public $environment;

	public function __construct()
	{
		$server_name = $_SERVER['SERVER_NAME'];
		$development = new EnvironmentDevelopment;
	
		if (in_array($server_name, $development->servers)) {
			$this->environment = $development;
		}
		else {
			$this->environment = new EnvironmentProduction;
		}

		return $this->environment;
	}
}