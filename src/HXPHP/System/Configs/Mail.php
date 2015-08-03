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

	public function setFrom($from, $from_mail)
	{
		$this->from = $from;
		$this->from_mail = $from_mail;

		return $this;
	}
}