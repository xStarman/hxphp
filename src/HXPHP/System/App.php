<?php

namespace HXPHP\System;
use HXPHP\System\Http as Http;

class App
{
	/**
	 * Injeção das configurações
	 * @var object
	 */
	public $configs;

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
	public function __construct(Configs\Config $configs)
	{
		$this->configs  = $configs;
		$this->request  = new Http\Request($configs->baseURI);
		$this->response = new Http\Response;
		
		return $this;
	}

	/**
	 * Configuração do ORM
	 */
	public function ActiveRecord()
	{
		$cfg = \ActiveRecord\Config::instance();
		$cfg->set_model_directory($this->configs->models->directory);
		$cfg->set_connections(
			array(
				'development' => 'mysql://'.$this->configs->database->user
									.':'.$this->configs->database->password
									.'@'.$this->configs->database->host
									.'/'.$this->configs->database->dbname
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
		$controller = $this->configs->controllers->directory . $this->request->controller.'.php';

		if ( ! file_exists($controller))
			$controller = $this->configs->controllers->directory . $this->configs->controllers->notFound . '.php';
		
		//Inclusão do Controller
		require_once($controller);

		//Verifica se a classe correspondente ao Controller existe
		if ( ! class_exists($this->request->controller)) {
			$this->request->controller = $this->configs->controllers->notFound;
		}

		//Instância do Controller
		$app = new $this->request->controller();
		
		//Verifica se a Action requisitada não existe
		if ( ! method_exists($app, $this->request->action))
			$this->request->action = 'indexAction';

		/**
		 * Adiciona a View ao Controller
		 */
		$app->setView(new View( $this->configs,
							   $this->request->controller,
							   $this->request->action ));

		/**
		 * Atribuição de parâmetros
		 */
		call_user_func_array(array(&$app, $this->request->action), $this->request->params);

		/**
		 * Renderização da VIEW
		 */
		$app->view->flush();

	}
}