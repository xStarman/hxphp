<?php

namespace HXPHP\System\Configs;

class Database
{
	public $host;
	public $user;
	public $password;
	public $dbname;

	public function __construct()
	{
		$this->host = 'localhost';
		$this->user = 'root';
		$this->password = '';
		$this->dbname = 'hxphp';

		return $this;
	}
}