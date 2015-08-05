<?php

namespace HXPHP\System;

class View
{
	private $title;
	private $controller;
	private $action;
	protected $path;
	protected $file;
	protected $vars;
	protected $header;
	protected $footer;

	public function __construct()
	{
		$this->title = 'HXPHP Framework';

	}


	/**
	 * Define o título da página
	 * @param string  $title     Título da página
	 * @param boolean $exclusive Define se o título será concatenado ou exclusivo
	 */
	public function setTitle($title)
	{
		$this->title = $title;
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