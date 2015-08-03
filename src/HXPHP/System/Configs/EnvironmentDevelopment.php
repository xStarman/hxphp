<?php

namespace HXPHP\System\Configs;

class EnvironmentDevelopment extends AbstractEnvironment
{
	public $servers;

	public function __construct()
	{
		parent::__construct();
		$this->servers = array(
			'localhost',
			'127.0.0.1'
		);

		return $this;
	}
}