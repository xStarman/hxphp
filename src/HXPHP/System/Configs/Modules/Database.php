<?php

namespace HXPHP\System\Configs\Modules;

class Database
{
	public $driver;
	public $host;
	public $user;
	public $password;
	public $dbname;
	public $charset;

	public function __construct()
	{
		$this->setConnectionData(array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'hxphp',
			'charset' => 'utf8'
		));
		return $this;
	}
	public function setConnectionData(array $data)
	{
		(array_key_exists('driver', $data)) ?
                    $this->driver = $data['driver'] :
                    $this->driver = 'mysql';
		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->dbname = $data['dbname'];
		(array_key_exists('charset', $data)) ?
                    $this->charset = $data['charset'] :
                    $this->charset = 'utf8';

		return $this;
	}
}
