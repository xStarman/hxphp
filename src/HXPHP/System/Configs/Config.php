<?php

namespace HXPHP\System\Configs;
use HXPHP\System\Tools;

class Config
{
	public $global;

	public function __construct()
	{
		$this->global = new GlobalConfig;
	}

	public function setEnv($environment)
	{
		$name = strtolower(Tools::filteredName($environment));
		$object = 'HXPHP\System\Configs\Environment' . ucfirst(Tools::filteredName($environment));

		if ( ! class_exists($object)) {
			throw new \Exception('O ambiente informado nao esta definido nas configuracoes do sistema.');
		}
		else {
			$this->$name = new $object();

			return $this->$name;
		}
	}
}