<?php
	//Constantes
	$configs = new HXPHP\System\Configs\Config;

	$configs->env->add('production');
	$configs->env->production->database->setConnectionData(array(
		'host' => 'localhost',
		'user' => 'root',
		'password' => '',
		'dbname' => 'teste'
	));
	$configs->define->setDefaultEnv('production');

	return $configs;
