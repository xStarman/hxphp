<?php

namespace HXPHP\System\Configs\Modules;

class Menu
{
	private $configs = array(
		'container' => false,
		'container_id' => '',
		'container_class' => '',
		'menu_id' => 'menu',
		'menu_class' => 'menu',
		'menu_active_class' => 'active',
		'menu_dropdown_class' => 'dropdown',
		'link_dropdown_class' => 'dropdown-toggle',
		'link_attrs' => array(
			'data-toggle' => 'dropdown'
		),
		'dropdown_class' => 'dropdown-menu',
		'dropdown_active_class' => 'active'
	);

	public function setConfigs(array $configs)
	{
		return $this->configs = array_merge($this->configs, $configs);
	}
}