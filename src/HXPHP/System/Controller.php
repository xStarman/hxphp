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
	 * @param  array  $params Parâmetros do método construtor
	 * @param  bool   $suffix Define se o sufixo será incluso na injeção
	 * @return object         Objeto injetado
	 */
	public function load($object, array $params = array(), $suffix = false)
	{
		$object = 'HXPHP\System\\' . $object;

		if (class_exists($object)) {
			$explode = explode('\\', $object);
			$name = end($explode);
			$name = strtolower(Tools::filteredName($name));

			if ($suffix === false) {
				$name = str_replace(
					array(
						'helper',
						'module',
						'service'
					),
					array(
						'',
						'',
						''
					),
					$name
				);
			}
			if ( ! empty($params)) {
				$ref = new \ReflectionClass($object);
  				$this->$name = $ref->newInstanceArgs($params);
			}
			else{
				$this->$name = new $object();
			}

			return $this->$name;
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