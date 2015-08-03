<?php

namespace HXPHP\System\Configs;

abstract class AbstractEnvironment
{
	public $database;
	public $mail;
	public $baseURI;

	public function __construct()
	{
		$this->database = new Database;
		$this->mail = new Mail;
		$this->baseURI = '/hxphp/';

		return $this;
	}
}