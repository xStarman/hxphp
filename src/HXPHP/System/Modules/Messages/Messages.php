<?php

namespace HXPHP\System\Modules\Messages;

class Messages
{
	/**
	 * Contém o conteúdo do template JSON
	 * @var array
	 */
	private $content;
	
	/**
	 * Método construtor
	 * @param string $template Nome do arquivo que encontra-se na sub-pasta 'templates'
	 */
	public function __construct($file)
	{
		/**
		 * Recebe o conteúdo JSON do template definido
		 * @var LoadTemplate
		 */
		$template = new LoadTemplate($file);

		/**
		 * JSON => ARRAY
		 * @var array
		 */
		if($template->getJson() !== false)
			$this->content = json_decode($template->getJson(), true);

		return $this;
	}

	/**
	 * Retorna o ARRAY com o grupo de mensagens de acordo com o bloco
	 * @param  string $param Nome do bloco
	 * @return array         Conteúdo do bloco
	 */
	public function __get($param)
	{
		return isset($this->content[$param]) ? $this->content[$param] : false;
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

			return array_values($alert); //RETORNO DIFERENTE
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

			return $message; //RETORNO DIFERENTE
		}
		return null;
	}
}