<?php

namespace HXPHP\System\Modules\Messages;

class LoadTemplate
{
	/**
	 * Método responsável pela leitura de arquivo JSON e retorno do conteúdo
	 * @param string $template Nome do template
	 */
	public function __construct($template)
	{
		/**
		 * Caminho completo do template
		 * @var string
		 */
		$template = dirname(__FILE__) . DS . 'templates' . DS . $template . '.json';

		if ( ! file_exists($template))
			throw new \Exception("O template nao foi localizado: <'$template'>", 1);

		$json  = file_get_contents($template);

		if (empty($json))
			return false;

		return $json;
	}
}