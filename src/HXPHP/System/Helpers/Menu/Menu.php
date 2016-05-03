<?php

namespace HXPHP\System\Helpers\Menu;

use HXPHP\System\Storage as Storage;
use HXPHP\System\Tools as Tools;

class Menu
{
	private $attrs = null;
	private $elements = null;

	/**
	 * Dados do módulo de configuração
	 * @var array
	 */
	private $configs = array();

	/**
	 * URL ATUAL
	 * @var string
	 */
	private $current_URL = null;

	private $role;

	/**
	 * Conteúdo HTML do menu renderizado
	 * @var string
	 */
	private $html;


	/**
	 * @param \HXPHP\System\Http\Request   $request Objeto Request
	 * @param \HXPHP\System\Configs\Config $configs Configurações do framework
	 * @param string                       $role    Nível de acesso
	 */
	public function __construct(
		\HXPHP\System\Http\Request $request,
		\HXPHP\System\Configs\Config $configs,
		$role = 'default'
	)
	{
		$this->attrs = new Attrs;
		$this->elements = new Elements;

		$this->role = $role;

		$this->setConfigs($configs)
				->setCurrentURL($request, $configs);
	}

	/**
	 * Dados do módulo de configuração do MenuHelper
	 * @param array $configs
	 */
	private function setConfigs($configs)
	{
		$this->configs = $configs;

		return $this;
	}

	/**
	 * Define a URL atual
	 */
	private function setCurrentURL($request, $configs)
	{
		$parseURL = parse_url($request->server('REQUEST_URI'));

		$this->current_URL = $configs->site->url . $parseURL['path'];

		return $this;
	}

	/**
	 * Verifica se o link está ativo
	 * @param  string $URL Link do menuy
	 * @return bool        Status do link
	 */
	private function checkActive($URL)
	{
		$position = strpos($this->current_URL, $URL);
		return $this->current_URL === $URL || ($position !== false && $position > 0) ? true : false;
	}

	/**
	 * Verifica se algum link do dropdown está ativo
	 * @param  array $values Links do dropdown
	 * @return bool        	 Status do dropdown
	 */
	private function checkDropdownActive(array $values)
	{
		$values = array_values($values);
		$status = false;

		foreach ($values as $dropdown_link) {
			$real_link = $this->getRealLink($dropdown_link);

			if ($this->checkActive($real_link) === true) {
				$status = true;
				break;
			}
		}
		return $status;
	}

	/**
	 * Extrair dados da key dos menus
	 * @param  string $key Titulo/Icone
	 * @return object      Objeto com dados extraídos
	 */
	private function extractingMenuData($key)
	{
		$obj = new \stdClass;

		$explode = explode('/', $key);

		$obj->title = $explode[0];
		$obj->icon = isset($explode[1]) ? $explode[1] : '';

		return $obj;
	}

	/**
	 * Retorna o link com os coringas preenchidos
	 * @param  string $value Link 
	 * @return string        Link tratado
	 */
	private function getRealLink($value)
	{
		$value = str_replace(
			array('% %', '%/'),
			array('%%', '%'),
			$value
		);

		return str_replace(
			array(
				'%siteURL%',
				'%site_URL',
				'%site_url',
				'%baseURI%',
				'%base_uri%'
			), 
			array(
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->site->url . $this->configs->baseURI,
				$this->configs->baseURI,
				$this->configs->baseURI
			),
			$value
		);
	}

	/**
	 * Renderiza o menu em HTML
	 */
	private function render($role = 'default')
	{
		$menus = $this->configs->menu->itens[$role];
		$menu_configs = $this->configs->menu->configs;

		if (empty($menus) || !is_array($menus))
			return false;

		$itens = '';

		$i = 0;

		foreach ($menus as $key => $value) {
			$i++;
			$menu_data = $this->extractingMenuData($key);
			$real_link = $this->getRealLink($value);

			// Dropdown
			if (is_array($value) && !empty($value)) { 
				$dropdown_itens = '';

				foreach ($value as $dropdown_key => $dropdown_value) {
					$submenu_data = $this->extractingMenuData($dropdown_key);
					$submenu_real_link = $this->getRealLink($dropdown_value);

					$submenu_link_active = $this->checkActive($submenu_real_link) === true ? $menu_configs['link_active_class'] : '';

					$link = $this->elements->get('link', array(
						$submenu_real_link,
						$menu_configs['link_class'],
						$submenu_link_active,
						$submenu_data->title,
						$submenu_data->icon,
						$menu_configs['link_before'],
						$submenu_data->title,
						$menu_configs['link_after']
					));

					$submenu_active = $this->checkActive($submenu_real_link) === true ? $menu_configs['dropdown_item_active_class'] : '';

					$dropdown_itens.= $this->elements->get('dropdown_item', array(
						$menu_configs['dropdown_item_class'],
						$submenu_active,
						$link
					));
				}

				$dropdown = $this->elements->get('dropdown', array(
					$i,
					$menu_configs['dropdown_class'],
					$dropdown_itens
				));

				$attrs = $this->attrs->render($menu_configs['link_dropdown_attrs']);
				$active = $this->checkDropdownActive($value) === true ? $menu_configs['link_active_class'] : '';

				$link = $this->elements->get('link_with_dropdown', array(
					$i,
					$menu_configs['link_dropdown_class'],
					$active,
					$attrs,
					$menu_data->title,
					$menu_data->icon,
					$menu_configs['link_before'],
					$menu_data->title,
					$menu_configs['link_after'],
					$dropdown
				));

				$active = $this->checkDropdownActive($value) === true ? $menu_configs['menu_item_active_class'] : '';

				$itens.= $this->elements->get('menu_item', array(
					$menu_configs['menu_item_dropdown_class'],
					$active,
					$link
				));		
			}
			else {
				$link_active = $this->checkActive($real_link) === true ? $menu_configs['link_active_class'] : '';

				$link = $this->elements->get('link', array(
					$real_link,
					$menu_configs['link_class'],
					$link_active,
					$menu_data->title,
					$menu_data->icon,
					$menu_configs['link_before'],
					$menu_data->title,
					$menu_configs['link_after']
				));

				$active = $this->checkActive($real_link) === true ? $menu_configs['menu_item_active_class'] : '';

				$itens.= $this->elements->get('menu_item', array(
					$menu_configs['menu_item_class'],
					$active,
					$link
				));
			}	
		}

		$menu = $this->elements->get('menu', array(
			$menu_configs['menu_class'],
			$menu_configs['menu_id'],
			$itens
		));

		if ($menu_configs['container'] !== false) {
			$this->html = $this->elements->get('container', array(
				$menu_configs['container'],
				$menu_configs['container_id'],
				$menu_configs['container_class'],
				$menu,
				$menu_configs['container']
			));
		}
		else {
			$this->html = $menu;
		}

		return $this;
	}

	/**
	 * Exibe o HTML com o menu renderizado
	 * @return string
	 */
	public function getMenu()
	{
		$this->render($this->role);

		return $this->html;
	}

	public function __toString()
	{
		return $this->getMenu();
	}
}