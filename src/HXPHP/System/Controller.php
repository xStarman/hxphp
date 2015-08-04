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
		$this->view    = new View;

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
        $this->render('Index');
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
	 * Renderiza a VIEW
	 * @param  string  $view     Nome do arquivo, sem extensão, a ser utilizado como VIEW
	 * @param  mixed   $data     Parâmetros que serão passados como variáveis para a VIEW
	 * @param  boolean $template Define se a VIEW será um miolo englobado por Header e Footer
	 * @param  string  $custom_header   Define um Header customizado
	 * @param  string  $custom_footer   Define um Footer customizado
	 * @param  array   $custom_css      Links de arquivos CSS a serem importados na VIEW
	 * @param  array   $custom_js       Links de arquivos JS a serem importados na VIEW
	 */
	protected function render($view, $data = array(),$template = true, $custom_header = '', $custom_footer = '', array $custom_css = array(), array $custom_js = array())
	{
		//Configuração dos parâmetros que serão passados para a VIEW
		$data = ! is_array($data) ? array() : $data;
		$this->view->setTitle($this->configs->title);
		$data = array_merge($this->data, $this->view->config, $data);

		//Extract que transforma os parâmetros em variáveis disponíveis para a VIEW
		if (count($data) > 0)
			extract($data, EXTR_PREFIX_ALL, 'view');

		//Inclusão de ASSETS
		$add_css = $this->view->assets('css', $custom_css);
		$add_js  = $this->view->assets('js', $custom_js);

		//Atribuição das constantes
		define('BASE',   $this->configs->baseURI);
		define('ASSETS', $this->configs->baseURI . 'public/assets/');
		define('IMG',    $this->configs->baseURI . 'public/img/');
		define('CSS',    $this->configs->baseURI . 'public/css/');
		define('JS',     $this->configs->baseURI . 'public/js/');

		//Verifica a existência da VIEW
		$view = $this->configs->views->directory . $view . $this->configs->views->extension;

		if ( ! file_exists($view))
			die("Erro fatal: A view <$view> não foi encontrada. Por favor, crie a view e tente novamente.");

		//Mecanismo de template
		if ( ! $template) {
			//Inclusão da view
			require_once($view);
			exit();
		}

		//Verifica a existência do Header e Footer customizado
		$header = $this->configs->views->directory . 'Header' . $custom_header . $this->configs->views->extension;
		$footer = $this->configs->views->directory . 'Footer' . $custom_footer . $this->configs->views->extension;

		if ( ! file_exists($header) || ! file_exists($footer))
			die("Erro fatal: O header <$header> ou o footer <$footer> não existe. Por favor, verifique e tente novamente.");

		//Inclusão dos arquivos
		require_once($header);
		require_once($view);
		require_once($footer);

		exit();
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