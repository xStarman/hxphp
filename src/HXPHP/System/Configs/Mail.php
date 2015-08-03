<?php

namespace HXPHP\System\Configs;

class Mail
{
	public $from;
	public $from_mail;

	public function __construct()
	{
		$this->from = 'HXPHP Framework';
		$this->from_mail = 'no-reply@hxphp.com.br';

		return $this;
	}
}