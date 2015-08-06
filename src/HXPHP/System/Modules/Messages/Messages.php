<?php

namespace HXPHP\System\Modules\Messages;

use HXPHP\System\Services as Services;

class Messages
{
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
}