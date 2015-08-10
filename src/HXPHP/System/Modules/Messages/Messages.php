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
	 * Caminho do arquivo
	 * @var string
	 */
	private $file;
	
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
		$this->file = $template->getTemplatePath();

		/**
		 * JSON => ARRAY
		 * @var array
		 */
		if($template->getJson() !== false)
			$this->content = json_decode($template->getJson(), true);

		return $this;
	}

	/**
	 * Retorna uma instância do objeto Template configurada mediante o bloco informado
	 * @param  string $param Nome do bloco
	 * @return object        Instância do objeto Template
	 */
	public function __get($param)
	{
		if(isset($this->content[$param])) {
			$this->$param = new Template($this->content[$param]);

			return $this->$param;
		}

		throw new \Exception("O bloco <'$param'> nao foi encontrado no template <'$this->file'>.", 1);
	}
}