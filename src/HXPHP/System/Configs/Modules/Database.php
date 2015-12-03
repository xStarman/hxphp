<?php

namespace HXPHP\System\Configs\Modules;

class Database
{
	public $driver;
	public $host;
	public $user;
	public $password;
	public $dbname;

	public function __construct()
	{
		$this->setConnectionData(array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'hxphp'
		));
		return $this;
	}
	public function setConnectionData(array $data)
	{
		$this->driver = $data['driver'];
		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->dbname = $data['dbname'];

		return $this;
	}
}