<?php

namespace HXPHP\System\Configs;

class Mail
{
	public $from;
	public $from_mail;

	public function __construct()
	{
		$this->setFrom('HXPHP Framework', 'no-reply@hxphp.com.br');
		return $this;
	}

	public function setFrom(array $data)
	{
		if (count($data) != 2) {
			throw new \Exception('Preencha todas os dados para configurar o disparo de e-mails. E necessario informar: from e from_mail.');
		}

		$this->from = $data['from'];
		$this->from_mail = $data['from_mail'];

		return $this;
	}
}