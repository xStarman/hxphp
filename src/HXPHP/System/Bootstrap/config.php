<?php
	
	/**
	 * Define a BASE da URL para ambiente de desenvolvimento e produção, respectivamente
	 */
	if ($_SERVER['SERVER_NAME'] === 'localhost'){
		define('BASE','/hxphp/');
	}
	else{
		define('BASE','/');
	}

	/**
	 * Constantes de diretórios
	 */
	define('SITE', 'http://' . $_SERVER['SERVER_NAME'] . BASE);
	define('DS', DIRECTORY_SEPARATOR);
	define('ROOT_PATH', str_replace('/', DS, BASE));
	define('PUBLIC_PATH', ROOT_PATH . 'public');

	//Constantes que definem as configurações para a aplicação
	$app_config = array(
		'VERSION' => '1.2.1',
		'VIEW_EXTENSION' => '.phtml',
		'CONTROLLER_NOT_FOUND' => 'Error404Controller'
	);

	//Constantes que definem as configurações da autenticação
	$auth_config = array(
		'URL_REDIRECT_AFTER_LOGIN' => SITE . 'home',
		'URL_REDIRECT_AFTER_LOGOUT' => SITE . 'login',
		'USER_STATUS_LOCKED' => 2
	);

	//Constantes que definem os diretórios principais do HXPHP
	$main_directories = array(
		'CONTROLLERS' => 'app' . DS . 'controllers' . DS,
		'MODELS' => 'app' . DS . 'models' . DS,
		'VIEWS' => 'app' . DS . 'views' . DS
	);

	//Constantes que definem os diretórios secundários do HXPHP
	$secondary_directories = array(
		'ASSETS' =>  PUBLIC_PATH . DS.'assets' . DS,
		'IMG' =>  PUBLIC_PATH . DS . 'img' . DS,
		'CSS' =>  PUBLIC_PATH . DS . 'css' . DS,
		'JS' =>  PUBLIC_PATH . DS . 'js' . DS
	);

	//Constante que define o título prefixado da aplicação
	$title_config = array(
		'TITLE' => 'HXPHP Framework - O seu primeiro framework'
	);

	//Constantes que configuram os headers do sistema de envio de e-mails
	$email_config = array(
		'REMETENTE' => 'HXPHP Framework',
		'EMAIL_REMETENTE' => 'no-reply@hxphp.com.br'
	);

	//Declaração das constantes
	$constants = array_merge($app_config, $auth_config, $main_directories, $secondary_directories, $title_config, $email_config);
	foreach($constants as $key => $value)
		define($key,$value);
