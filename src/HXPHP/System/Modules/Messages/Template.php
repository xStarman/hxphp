<?php

namespace HXPHP\System\Modules\Messages;

class Template
{
	/**
	 * Conteúdo do template JSON
	 * @var array
	 */
	private $content;

	public function __construct($content)
	{
		$this->content = $content;
	}

	public function getByCode($code, $params = array(), $field = 'messages')
	{
		if (isset($this->content[$code])){
			$output = $this->content[$code];

			if ( ! empty($params) )
				$output[$field] = vsprintf($output[$field], $params);

			return $output;
		}

		throw new \Exception("O codigo <'$code'> nao foi encontrado. Verifique o template.", 1);
	}
}