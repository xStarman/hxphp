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
		$this->setConnectionData('localhost', 'root', '', 'hxphp');
		return $this;
	}
	public function setConnectionData($host, $user, $password, $dbname)
	{
		$this->host = $host;
		$this->user = $user;
		$this->password = $password;
		$this->dbname = $dbname;
		
		return $this;
	}
}