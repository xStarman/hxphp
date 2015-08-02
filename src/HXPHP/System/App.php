<?php

namespace HXPHP\System;
use HXPHP\System\Http as Http;

class App
{

	/**
	 * Injeção do Request
	 * @var object
	 */
	public $request;

	/**
	 * Injeção do Response
	 * @var object
	 */
	public $response;
	
	/**
	 * Método Construtor
	 */
	public function __construct()
	{
		$this->request  = new Http\Request;
		$this->response = new Http\Response;
		
		return $this;
	}

	/**
	 * Configuração do ORM
	 */
	public function ActiveRecord()
	{
		$cfg = \ActiveRecord\Config::instance();
		$cfg->set_model_directory(MODELS);
		$cfg->set_connections(
			array(
				'development' => 'mysql://'.USER.':'.PASS.'@'.HOST.'/'.DBNAME
			)
		);
	}
	
	/**
	 * Executa a aplicação
	 */
	public function run()
	{

		/**
		 * Caminho do controller
		 * @var string
		 */
		$controller = CONTROLLERS.$this->request->controller.'.php';

		if ( ! file_exists($controller))
			$controller = CONTROLLERS.CONTROLLER_NOT_FOUND.'.php';
		
		//Inclusão do Controller
		require_once($controller);

		//Verifica se a classe correspondente ao Controller existe
		if ( ! class_exists($this->request->controller)) {
			$this->request->controller = CONTROLLER_NOT_FOUND;
		}

		//Instância do Controller
		$app = new $this->request->controller();
		
		//Verifica se a Action requisitada não existe
		if ( ! method_exists($app, $this->request->action))
			$this->request->action = 'indexAction';

		//Atribuição de parâmetros
		call_user_func_array(array(&$app, $this->request->action), $this->request->params);
	}
}