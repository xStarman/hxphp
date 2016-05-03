<?php
	//Constantes
	$configs = new HXPHP\System\Configs\Config;

	$configs->env->add('development');

	$configs->env->development->menu->setConfigs(array(
		'container' => 'nav',
		'container_class' => 'navbar navbar-default',
		'menu_class' => 'nav navbar-nav'
	));

	$configs->env->development->menu->setMenus(array(
		'Home/home' => '%siteURL%',
		'Subpasta/folder-open' => array(
			'Home/home' => '%baseURI%/admin/have-fun/',
			'Teste/home' => '%baseURI%/admin/index/',
		)
	));

	/*
		//Globais
		$configs->global->models->directory = APP_PATH . 'models' . DS;

		$configs->global->views->directory = APP_PATH . 'views' . DS;
		$configs->global->views->extension = '.phtml';

		$configs->global->controllers->directory = APP_PATH . 'controllers' . DS;
		$configs->global->controllers->notFound = 'Error404Controller';

		$configs->title = 'Titulo customizado';

		//Configurações de Ambiente - Desenvolvimento
		$configs->env->add('development');

		$configs->env->development->baseURI = '/hxphp/';

		$configs->env->development->database->setConnectionData(array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'hxphp',
			'charset' => 'utf8'
		));

		$configs->env->development->mail->setFrom(array(
			'from' => 'Remetente',
			'from_mail' => 'email@remetente.com.br'
		));

		$configs->env->development->menu->setConfigs(array(
			'container' => 'nav',
			'container_class' => 'navbar navbar-default',
			'menu_class' => 'nav navbar-nav'
		));

		$configs->env->development->menu->setMenus(array(
			'Home/home' => '%siteURL%',
			'Subpasta/folder-open' => array(
				'Home/home' => '%baseURI%/admin/have-fun/',
				'Teste/home' => '%baseURI%/admin/index/',
			)
		));

		//Configurações de Ambiente - Produção
		$configs->env->add('production');

		$configs->env->production->baseURI = '/';

		$configs->env->production->database->setConnectionData(array(
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'usuariodobanco',
			'password' => 'senhadobanco',
			'dbname' => 'hxphp',
			'charset' => 'utf8'
		));

		$configs->env->production->mail->setFrom(array(
			'from' => 'Remetente',
			'from_mail' => 'email@remetente.com.br'
		));
	*/
	 

	return $configs;
