<?php

use \HXPHP\System\Configs\Config;

$config = new Config;
$config->global->title = 'Teste';

$config->setEnv('development');
$config->development->database->setConnectionData(array(
	'host' => 'localhost',
	'user' => 'root',
	'password' => '',
	'dbname' => ''
));

echo '<pre>';
print_r($config);
echo '</pre>';