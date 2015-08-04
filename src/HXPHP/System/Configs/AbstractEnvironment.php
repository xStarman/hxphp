<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Configs\Modules as Modules;

abstract class AbstractEnvironment
{
	public $baseURI;
	private $load;

	public function __construct()
	{

		$this->baseURI = '/hxphp/';

		$this->load = new LoadModules;

		return $this->load->loadModules($this);
	}
}