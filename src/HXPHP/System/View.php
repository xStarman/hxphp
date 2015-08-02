<?php

namespace HXPHP\System;

use HXPHP\System\Helpers as Helpers;

class View
{

	/**
	 * Injeção da Aplicação
	 * @var object
	 */
	protected $app;

	/**
	 * Injeção do MenuHelper;
	 * @var object
	 */
	protected $menu;

	/**
	 * Parâmetros globais da VIEW
	 * @var array
	 */
	public $config = array();

	/**
	 * Método Construtor
	 */
	public function __construct()
	{
		//Instância dos objetos injetados
		$this->app = new App;
		$this->menu = new Helpers\MenuHelper;

		//Configuração básica
		$this->config['title'] = TITLE;

		return $this;
	}

	/**
	 * Define o título da página
	 * @param string  $title     Título da página
	 * @param boolean $exclusive Define se o título será concatenado ou exclusivo
	 */
	public function setTitle($title, $exclusive = false)
	{
		$this->config['title'] = $exclusive !== false ? $title : TITLE.' - '.$title;
	}

	/**
	 * Define o nível de acesso do usuário para obter o menu
	 * @param string $role Nível de acesso do usuário
	 */
	public function setMenu($role)
	{
		return $this->menu->render($role, $this->app->request->controller);
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