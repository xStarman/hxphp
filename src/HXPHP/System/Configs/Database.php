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
	public function setConnectionData(array $data)
	{
		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->dbname = $data['dbname'];

		return $this;
	}
}