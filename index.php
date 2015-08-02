<?php
	use HXPHP\System\App;
	use HXPHP\System\Services\StartSession;
	ob_start();

	ini_set('display_errors', 1); 
	date_default_timezone_set('America/Sao_Paulo');

	//Inclusão dos arquivos de configuração
	require_once("src/HXPHP/System/Bootstrap/config.php");
	require_once("src/HXPHP/System/Bootstrap/database.php");

	/**
	 * Verifica se o autoload do Composer está configurado
	 */
	$composer_autoload = 'vendor' . DS . 'autoload.php';

	if ( ! file_exists($composer_autoload)) {
		die('Execute o comando: composer install');
	}

	require_once($composer_autoload);

	//Start da sessão
	StartSession::sec_session_start();

	$app = new App();
	$app->ActiveRecord();
	$app->run();