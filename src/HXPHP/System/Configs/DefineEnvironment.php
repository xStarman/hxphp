<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Tools;

class DefineEnvironment
{
	public $environment;

	public function __construct()
	{
		$server_name = $_SERVER['SERVER_NAME'];
		$development = new EnvironmentDevelopment;
	
		if (in_array($server_name, $development->servers)) {
			$this->environment = 'development';
		}
		else {
			$this->environment = 'production';
		}

		return $this;
	}

	public function setEnv($config, $environment)
	{
		$name = strtolower(Tools::filteredName($environment));
		$object = 'HXPHP\System\Configs\Environment' . ucfirst(Tools::filteredName($environment));

		if ( ! class_exists($object)) {
			throw new \Exception('O ambiente informado nao esta definido nas configuracoes do sistema.');
		}
		else {
			$config->$name = new $object();

			return $config->$name;
		}
	}
}