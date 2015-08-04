<?php

namespace HXPHP\System\Configs\Environments;

use HXPHP\System\Configs\Modules as Modules;

abstract class AbstractEnvironment
{
	public $database;
	public $mail;
	public $baseURI;

	public function __construct()
	{
		$this->database = new Modules\Database;
		$this->mail = new Modules\Mail;
		$this->baseURI = '/hxphp/';

		return $this;
	}
}