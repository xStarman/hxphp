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
		 * Conteúdo do container
		 * Tag final container
		 */
		'container' => '
			<%s>
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
		'link' => '<a href="%s" class="%s %s" title="%s"><i class="fa fa-%s"> %s%s%s</a>',

		/**
		 * Classe
		 * Classe Ativa
		 * Attrs
		 * Título
		 * Ícone
		 * Before
		 * Título
		 * After
		 */
		'link_with_dropdown' => '
			<a href="javascript: void(0);" class="%s %s" %s title="%s">
				<i class="fa fa-%s"> %s%s%s <i class="arrow fa fa-angle-down pull-right"></i>
			</a>
		',

		/**
		 * Classe dropdown
		 * Conteúdo
		 */
		'dropdown' => '<ul class="%s">%s</ul>',

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
		$this->setConfigs($configs->menu->configs)
				->setCurrentURL($request, $configs);
	}

	/**
	 * Dados do módulo de configuração do MenuHelper
	 * @param array $configs
	 */
	private function setConfigs(array $configs)
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
	 * Define o Array com menus e submenus
	 * @param  string $role Role do usuário
	 */
	private function setMenu($role)
	{	
		switch ($role) {
			case 'administrator':
				$this->menu = array(
					'Home/home' => 'home',
					'Projetos/briefcase' => 'projetos/listar/',
					'Clientes/users' => array(
						'Listar todos' => 'clientes/show',
						'Tipos de clientes' => 'clientes/tipos'
					),
					'Usuários/users' => 'usuarios/listar/'
				);
				break;

			case 'user':
				$this->menu = array(
					'Home/home' => 'home',
					'Projetos/briefcase' => 'projetos/listar/'
				);
				break;
		}

		return $this;
	}

	/**
	 * Renderiza o menu em HTML
	 */
	private function render($role = 'default')
	{
		$menus = $this->configs->menu->itens;
		$menu_configs = $this->configs->menu->configs;



		$menu = $this->getElement('menu', array(
			$menu_configs['menu_class'],
			$menu_configs['menu_id'],
			$itens
		));

		if ($menu_configs['container'] !== false) {
			$this->html = $this->getElement('container', array(
				$menu_configs['container'],
				$menu,
				$menu_configs['container']
			));
		}

		foreach ($this->menu as $key => $value) {
			$explode = explode('/', $key);

			$title = $explode[0];
			$icon =  isset($explode[1]) ? $explode[1] : '';

			/**
			 * Menu com submenus
			 */
			if (is_array($value)) {
				$values = array_values($value);
				$check = explode('/', $values[0]);

				$this->html .= '
				    <li class="dropdown '.(($check[0] == $controller) ? 'active' : '').'">
				      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span> <i class="arrow fa fa-angle-down pull-right"></i></a>
				      <ul class="dropdown-menu">
				';

				foreach($value as $titulo => $link){
					$this->html .= '
						<li><a href="'.$this->baseURI.$link.'">'.$titulo.'</a></li>';
				}

				$this->html .= '
					  </ul>
					</li>';
			}
			/**
			 * Apenas Menu
			 */
			else {
				$this->html .= '<li '.((strpos($value, $controller) !== false) ? 'class="active"' : '').'>
									<a href="'.$this->baseURI.$value.'">
										<i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span>
									</a>
								</li>';
			}
		}

		$this->html .= '</ul>';

		return $this;
	}

	/**
	 * Exibe o HTML com o menu renderizado
	 * @return string
	 */
	public function getMenu()
	{
		$this->setHTML($this->controller);
		return $this->html;
	}

	/**
	 * Exibe o HTML com o menu renderizado
	 * @return string
	 */
	public function __toString()
	{
		return $this->getMenu();
	}
}