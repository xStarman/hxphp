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
		if (count($data) != 4) {
			throw new \Exception('Preencha todas os dados para estabelecer uma conexao com banco de dados. E necessario informar: host, user, password e dbname.');
		}

		$this->host = $data['host'];
		$this->user = $data['user'];
		$this->password = $data['password'];
		$this->dbname = $data['dbname'];

		return $this;
	}
}