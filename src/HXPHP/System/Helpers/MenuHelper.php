<?php

namespace HXPHP\System\Helpers;
use HXPHP\System\Storage as Storage;

class MenuHelper
{

	/**
	 * Injeção do Storage
	 * @var object
	 */
	private $storage;

	public function __construct()
	{
		//Instância dos objetos injetados
		$this->storage = new Storage\Session;

		return $this;
	}

	/**
	 * Define o Array com menus e submenus
	 * @param  string $role Role do usuário
	 * @return array  $menu Array com os menus
	 */
	private function menus($role)
	{
		switch ($role) {
			case 'administrator':
				$menus = array(
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
				$menus = array(
					'Home/home' => 'home',
					'Projetos/briefcase' => 'projetos/listar/'
				);
				break;

			default:
				$menus = array();
				break;
		}
		return $menus;
	}

	/**
	 * Renderiza o menu em HTML
	 * @param  object $user       Usuário logado
	 * @param  string $controller Controller atual
	 * @return html
	 */
	public function render($role, $controller)
	{
		if (is_null($role) || is_null($controller))
			return;

		$menus = self::menus($role);
		$controller = strtolower(str_replace('Controller', '', $controller));
		$html = '';

		foreach ($menus as $key => $value) {
			$explode = explode('/',$key);

			$title = $explode[0];
			$icon = $explode[1];

			if (is_array($value)) {
				$values = array_values($value);
				$check = explode('/',$values[0]);

				$html .= '
				    <li class="dropdown '.(($check[0] == $controller) ? 'active' : '').'">
				      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span> <i class="arrow fa fa-angle-down pull-right"></i></a>
				      <ul class="dropdown-menu">
				';

				foreach($value as $titulo => $link){
					$html .= '<li><a href="'.SITE.$link.'">'.$titulo.'</a></li>';
				}

				$html .= '</ul></li>';
			}else{
				$html .= '<li '.((strpos($value, $controller) !== false) ? 'class="active"' : '').'><a href="'.SITE.$value.'"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span></a></li>';
			}
		}
		return $this->storage->set('menu', $html);
	}

	public function show()
	{
		$menu = $this->storage->get('menu');
		$this->storage->clear('menu');

		return $menu;
	}
}