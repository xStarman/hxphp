<?php 

namespace HXPHP\System;
use HXPHP\System\Http as Http;

class Controller
{
	/**
	 * Injeção das Configurações
	 * @var object
	 */
	private $configs;

	/**
	 * Injeção do Http Request
	 * @var object
	 */
	private $request;

	/**
	 * Injeção do Http Response
	 * @var object
	 */
	private $response;

	/**
	 * Injeção da View
	 * @var object
	 */
	public $view;


	/**
	 * Método Construtor
	 */
	public function __construct()
	{
		//Instância dos objetos injetados
		$this->request = new Http\Request;

		return $this;
	}

	/**
	 * Injeção da VIEW
	 * @param View $view View atual
	 */
	public function setView(View $view)
	{
		$this->view = $view;
		return $this;
	}

	/**
	 * Default Action
	 */
	public function indexAction()
    {
    	
    }

	/**
	 * Carrega serviços, módulos e helpers
	 * @param  string $object Nome da classe
	 * @param  string|array  $params Parâmetros do método construtor
	 * @return object         Objeto injetado
	 */
	public function load($object, $params = array())
	{
		$params = array($params);

		/**
		 * Tratamento que adiciona a pasta do módulo
		 */
		$explode = explode('\\', $object);
		$object = ($explode[0] === 'Modules' ? $object . '\\' . end($explode) : $object);
		$object = 'HXPHP\System\\' . $object;

		if (class_exists($object)) {
			$name = end($explode);
			$name = strtolower(Tools::filteredName($name));

			if ( ! empty($params)) {
				$ref = new \ReflectionClass($object);
  				$this->view->$name = $ref->newInstanceArgs($params);
			}
			else{
				$this->view->$name = new $object();
			}

			return $this->view->$name;
		}
	}

	/**
	 * Método mágico para atalho de objetos injetados na VIEW
	 * @param  string $param Atributo
	 * @return mixed         Conteúdo do atributo ou Exception
	 */
	public function __get($param)
	{
		if (isset($this->view->$param)) {
			return $this->view->$param;
		}
		elseif (isset($this->$param)) {
			return $this->$param;
		}
		else {
			throw new \Exception("Parametro <$param> nao encontrado.", 1);
		}

	}
	
	/**
	 * Redirecionamento
	 * @param  string $url Link de redirecionamento
	 */
	public function redirectTo($url)
	{
		$this->response = new Http\Response;
		return $this->response->redirectTo($url);
	}
}