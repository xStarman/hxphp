<?php

namespace HXPHP\System\Configs;

class Config
{
	public $global;

	public function __construct()
	{
		$this->global = new Global;
	}
}