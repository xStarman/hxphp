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
	 * Configurações genéricas para todas as actions
	 * @var array
	 */
	public $data = array();


	/**
	 * Método Construtor
	 */
	public function __construct()
	{
		//Instância dos objetos injetados
		$this->request = new Http\Request;

		return $this;
	}

	public function setConfigs(Configs\Config $configs)
	{
		$this->configs = $configs;
	}

	/**
	 * Default Action
	 */
	public function indexAction()
    {
    	$this->view->setTitle('Seja bem vindo');
        $this->view->flush('Index');
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