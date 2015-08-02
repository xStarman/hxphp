<?php

class Messages{

	/**
	 * Injeção do serviço de E-mail
	 * @var object
	 */
	protected $email;

	/**
	 * Prefixo dos assuntos das mensagens que serão enviadas
	 * @var string
	 */
	private $prefix;

	/**
	 * Configuração do serviço de E-mail
	 * @var array
	 */
	private $config_email;

	/**
	 * Objeto com as mensagens do template selecionado
	 * @var object
	 */
	public $messages;

	/**
	 * Objeto com os alertas do template selecionado
	 * @var object
	 */
	public $alerts;
	
	/**
	 * Método construtor
	 * @param string $template Nome do arquivo que encontra-se na sub-pasta 'templates'
	 */
	public function __construct($template)
	{
		//Instância dos objetos injetados
		$this->email = new Email;

		//Configuração do serviço de E-mail
		$this->prefix = REMETENTE.' - ';
		$this->config_email = array(
			'remetente' => REMETENTE,
			'email' => EMAIL_REMETENTE
		);

		//Decodifica o template
		$template = dirname(__FILE__) . DS . 'templates' . DS . $template . '.json';

		if ( ! file_exists($template))
			throw new \Exception("O template para a mensagem nao foi localizado: $template", 1);

		$json  = file_get_contents($template);

		if(empty($json))
			return false;

		$content = json_decode($json,true);

		$this->messages = isset($content['messages']) ? $content['messages'] : null;
		$this->alerts   = isset($content['alerts']) ? $content['alerts'] : null;

		return $this;
	}

	/**
	 * Retorna os avisos
	 * @param  string $code   Código do aviso
	 * @param  array  $params Parâmetros de substituição
	 * @return array          Estrutura do aviso selecionado
	 */
	public function getAlert($code, array $params = array())
	{
		if (isset($this->alerts[$code])){
			$alert = $this->alerts[$code];

			if ( ! empty($params) )
				$alert['message'] = vsprintf($alert['message'], $params);

			return array_values($alert);
		}
		return null;
	}

	/**
	 * Retorna as mensagens
	 * @param  string $code   Código da mensagem
	 * @param  array  $params Parâmetros de substituição
	 * @return mixed          Estrutura da mensagem selecionada
	 */
	public function getMessage($code, array $params = array())
	{
		if (isset($this->messages[$code])){
			$message = $this->messages[$code];

			if ( ! empty($params) )
				$message['message'] = vsprintf($message['message'], $params);

			return $message;
		}
		return null;
	}

	/**
	 * Envia uma mensagem do template por e-mail
	 * @param  string $email   Endereço de e-mail do destinatário
	 * @param  string $message Mensagem que será enviada
	 * @return boolean         Status do processo
	 */
	public function sendEmail($email, $message)
	{
		if (isset($message['subject']) && isset($message['content']))
			return false;

		$subject = $message['subject'];
		$message = $message['message'];

		return $this->email->send($email, $this->prefix.$subject, $message, $this->config_email);
	}
}