<?php

namespace HXPHP\System\Services\Email;

class Email
{
	private $from = null;

	public function setFrom(array $from = array())
	{
		$this->from = $from;

		return $this;
	}
	
	/**
	 * Envia e-mail
	 * @param  string $to    E-mail para qual será enviada a mensagem
	 * @param  string $assunto  Assunto da mensagem
	 * @param  string $message Mensagem
	 * @param  array  $from   Array com Remetente e E-mail do remetente
	 * @return bool             Status de envio e mensagem
	 */
	public function send(
		$to,
		$subject,
		$message,
		array $from = array()
	)
	{
		$to = strtolower($to);
		$subject = addslashes(trim($subject));
		$message = nl2br($message);

		$from = !is_null($this->from) && empty($from) ? $this->from : $from;
		
		ksort($from);
		list($from_mail, $from) = $from;

		$headers = "MIME-Version: 1.0\n";
		$headers.= "Content-Type: text/html; charset=UTF-8\n";
		$headers.= "From: \"{$from}\" <{$from_mail}>\n";

		return @mail ($to, $subject, $message, $headers);
	}
}