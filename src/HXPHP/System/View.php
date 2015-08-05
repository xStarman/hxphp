<?php

namespace HXPHP\System;

class View
{
	public $title;

	private $configs;

	protected $path;
	protected $header;
	protected $file;
	protected $footer;
	protected $vars = array();
	
	

	public function __construct(Configs\Config $configs, $controller, $action)
	{
		/**
		 * Injeção das Configurações
		 * @var object
		 */
		$this->configs = $configs;

		/**
		 * Tratamento das variáveis
		 */
		$controller = strtolower(str_replace('Controller', '', $controller));
		$action = ($controller == $configs->controllers->notFound
					 ? 'indexAction' : $action);
		$action = str_replace('Action', '', $action);

		/**
		 * Definindo estrutura padrão
		 */
		$this->setPath($controller);
		$this->setHeader('Header');
		$this->setFile($action);
		$this->setFooter('Footer');

		/**
		 * Definindo dados 
		 */
		$this->setTitle('HXPHP Framework');
	}

	/**
	 * Define o título da página
	 * @param string  $title  Título da página
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}

	/**
	 * Define a pasta da view
	 * @param string  $path  Caminho da View
	 */
	public function setPath($path)
	{
		$this->path = $path;
	}

	/**
	 * Define o cabeçalho da view
	 * @param string  $header  Cabeçalho da View
	 */
	public function setHeader($header)
	{
		$this->header = $header;
	}

	/**
	 * Define o arquivo da view
	 * @param string  $file  Arquivo da View
	 */
	public function setFile($file)
	{
		$this->file = $file;
	}

	/**
	 * Define o rodapé da view
	 * @param string  $footer  Rodapé da View
	 */
	public function setFooter($footer)
	{
		$this->footer = $footer;
	}

	/**
	 * Inclui os arquivos customizados
	 * @param  string $type          Tipo de arquivo incluso, como: css ou js
	 * @param  array  $custom_assets Links dos arquivos que serão incluídos
	 * @return string                HTML formatado de acordo com o tipo de arquivo
	 */
	public function assets($type, array $custom_assets = array())
	{
		$add_assets = '';

		switch ($type) {
			case 'css':
				$tag = '<link type="text/css" rel="stylesheet" href="%s">'."\n\r";
				break;

			case 'js':
				$tag = '<script type="text/javascript" src="%s"></script>'."\n\r";
				break;
		}
		
		if (count($custom_assets) > 0)
			foreach ($custom_assets as $file)
				$add_assets .= sprintf($tag,$file);

		return $add_assets;
	}
}