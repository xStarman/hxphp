<?php

namespace HXPHP\System\Configs\Modules;

class Menu
{
	public $configs = array(
		'container' => false,
		'container_id' => '',
		'container_class' => '',
		'menu_id' => 'menu',
		'menu_class' => 'menu',
		'menu_item_class' => 'menu-item',
		'menu_item_active_class' => 'active',
		'menu_item_dropdown_class' => 'dropdown',
		'link_before' => '<span>',
		'link_after' => '</span>',
		'link_class' => 'menu-link',
		'link_active_class' => 'menu-active-link',
		'link_dropdown_class' => 'dropdown-toggle',
		'link_dropdown_attrs' => array(
			'data-toggle' => 'dropdown'
		),
		'dropdown_class' => 'dropdown-menu',
		'dropdown_item_class' => 'dropdown-item',
		'dropdown_item_active_class' => 'active'
	);

	public function setConfigs(array $configs)
	{
		return $this->configs = array_merge($this->configs, $configs);
	}
}