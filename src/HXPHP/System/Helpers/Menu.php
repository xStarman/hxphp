<?php

namespace HXPHP\System\Helpers;

use HXPHP\System\Storage as Storage;
use HXPHP\System\Tools as Tools;

class Menu
{
	/**
	 * Elementos HTML utilizados na renderização do menu
	 * @var array
	 */
	private $elements = array(

		/**
		 * Tag inicio container
		 * ID
		 * Classe
		 * Conteúdo do container
		 * Tag final container
		 */
		'container' => '
			<%s id="%s" class="%s">
				%s
			</%s>
		',

		/**
		 * Classe do menu
		 * ID do menu
		 * Conteúdo do menu
		 */
		'menu' => '<ul class="%s" id="%s">%s</ul>',

		/**
		 * Classe
		 * Classe ativa
		 * Conteúdo
		 */
		'menu_item' => '<li class="%s %s">%s</li>',

		/**
		 * Link
		 * Classe
		 * Classe ativa
		 * Título
		 * Ícone (Font-Awesome)
		 * Before
		 * Título
		 * After
		 */
		'link' => '<a href="%s" class="%s %s" title="%s"><i class="fa fa-%s"></i> %s%s%s</a>',

		/**
		 * Link
		 * Classe
		 * Classe Ativa
		 * Attrs
		 * Título
		 * Ícone
		 * Before
		 * Título
		 * After
		 * Dropdown
		 */
		'link_with_dropdown' => '
			<a href="#hxphp-submenu-%s" class="%s %s" %s title="%s">
				<i class="fa fa-%s"> %s%s%s <i class="arrow fa fa-angle-down pull-right"></i>
			</a>
			%s
		',

		/**
		 * ID dropdown
		 * Classe dropdown
		 * Conteúdo
		 */
		'dropdown' => '<ul id="hxphp-submenu-%s" class="%s">%s</ul>',

		/**
		 * Classe
		 * Classe ativa
		 * Conteúdo
		 */
		'dropdown_item' => '<li class="%s %s">%s</li>'
	);

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
		$role = null
	)
	{
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
		$site_url = $configs->site->url . $configs->baseURI;

		$subfolder = Tools::decamelize(str_replace(DS, '/', $request->subfolder));
		$controller = Tools::decamelize(str_replace('Controller', '', $request->controller));
		$action = Tools::decamelize(str_replace('Action', '', ucfirst($request->action)));

		$controller = $controller === 'index' ? '' : $controller . '/';
		$action = $action === 'index' ? '' : $action;

		$this->current_URL = $site_url . $subfolder . $controller . $action;

		return $this;
	}

	/**
	 * Retorna um elemento
	 * @param  string $name Nome do elemento
	 * @param  array  $args Array para preencher os coringas presentes nos elementos
	 * @return string       HTML do elemento
	 */
	private function getElement($name, array $args = array())
	{
		if ( ! isset($this->elements[$name]))
			return false;

		if ( ! empty($args)) {
			$args = array_values($args);
			array_unshift($args, $this->elements[$name]);

			return call_user_func_array('sprintf', $args);
		}

		return $this->elements[$name];
	}

	/**
	 * Verifica se o link está ativo
	 * @param  string $URL Link do menuy
	 * @return bool        Status do link
	 */
	private function checkActive($URL)
	{
		return strpos($this->current_URL, $URL) !== false ? true : false;
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

			if (strpos($this->current_URL, $real_link) !== false) {
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
	 * Renderização dos atributos extras para links de ativação para dropdown
	 * @param  array  $attrs Atributos
	 * @return string        Atributos no formato HTML
	 */
	private function renderAttrs($attrs)
	{
		if (empty($attrs) || !is_array($attrs))
			return null;

		$html = '';

		foreach ($attrs as $attr => $value) {
			$html.= ' ' . $attr . '="' . $value . '" ';
		}

		return $html;
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

					$link = $this->getElement('link', array(
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

					$dropdown_itens.= $this->getElement('dropdown_item', array(
						$menu_configs['dropdown_item_class'],
						$submenu_active,
						$link
					));
				}

				$dropdown = $this->getElement('dropdown', array(
					$i,
					$menu_configs['dropdown_class'],
					$dropdown_itens
				));

				$attrs = $this->renderAttrs($menu_configs['link_dropdown_attrs']);
				$active = $this->checkDropdownActive($value) === true ? $menu_configs['link_active_class'] : '';

				$link = $this->getElement('link_with_dropdown', array(
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

				$itens.= $this->getElement('menu_item', array(
					$menu_configs['menu_item_dropdown_class'],
					$active,
					$link
				));		
			}
			else {
				$link_active = $this->checkActive($real_link) === true ? $menu_configs['link_active_class'] : '';

				$link = $this->getElement('link', array(
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

				$itens.= $this->getElement('menu_item', array(
					$menu_configs['menu_item_class'],
					$active,
					$link
				));
			}	
		}

		$menu = $this->getElement('menu', array(
			$menu_configs['menu_class'],
			$menu_configs['menu_id'],
			$itens
		));

		if ($menu_configs['container'] !== false) {
			$this->html = $this->getElement('container', array(
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
	public function getMenu($role)
	{
		$this->render($role);

		return $this->html;
	}
}