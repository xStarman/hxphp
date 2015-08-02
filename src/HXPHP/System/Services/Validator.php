<?php

class Validator
{
	/**
	 * Injeção do módulo de mensagens
	 * @var object
	 */
	private $messages;

	/**
	 * Atributo que define o status da validação
	 * @var boolean
	 */
	public $valid = false;

	/**
	 * Armazena o status de todas as validações
	 * @var array
	 */
	private $validCheckPoint = array();

	/**
	 * Array com os erros
	 * @var array
	 */
	private $errors = array();

	/**
	 * Classe CSS da mensagem de erro
	 * @var string
	 */
	private $style = 'danger';

	/**
	 * Título da mensagem de erro
	 * @var string
	 */
	private $title_error = 'Ocorreram erros durante a validação. Por favor, verifique os campos listados abaixo:';


	/**
	 * Array que será validado
	 * @var array
	 */
	public $request = array();

	/**
	 * Método construtor
	 * @param array $requestArray Array com os dados a serem validados
	 */
	public function __construct(array $requestArray)
	{
		//Instância dos objetos injetados
		$this->messages = new Messages('validator');

		$this->request = $requestArray;
		if (empty($this->request))
			throw new \Exception('Oops, o ARRAY a ser tratado esta vazio!', 1);
	}
	
	/**
	 * Valida se os campos determinados estão preenchidos
	 * @param  array|string  $fields Campo(s) a ser(em) validado(s)
	 * @return boolean     			 Status da validação
	 */
	public function field_filledIn($fields = null)
	{
		if (is_array($fields)) {
			$status = array();

			foreach ($fields as $key => $value) {
				if ( ! array_key_exists($value, $this->request) || empty($this->request[$value]))
					array_push($status, $this->setError('campo-obrigatorio', $value));
			}

			return ! in_array(false, $status) ? $this->valid(true) : $this->valid(false);
		}
		elseif ( ! is_null($fields)) {
			if (array_key_exists($fields, $this->request) && !empty($this->request[$fields]))
				return $this->valid(true);

			return $this->setError('campo-obrigatorio', $fields);
		}
	}

	/**
	 * Multiplica os dígitos pelas posições
	 * @param  string  $digitos      Dígitos
	 * @param  integer $posicoes     Posições
	 * @param  integer $soma_digitos Soma dos digitos
	 * @return string                Dígitos atualizados
	 */
	private function calcular_posicoes_dos_digitos($digitos, $posicoes = 10, $soma_digitos = 0)
	{
		// Faz a soma dos dígitos com a posição
		for ($i = 0; $i < strlen($digitos); $i++)
		{
			// Preenche a soma com o dígito vezes a posição
			$soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
			$posicoes--;
			// Parte específica para CNPJ
			if ($posicoes < 2) 
				$posicoes = 9;
		}
		// Captura o resto da divisão entre $soma_digitos dividido por 11
		$soma_digitos = $soma_digitos % 11;

		if ($soma_digitos < 2){
			$soma_digitos = 0;
		}else{
			$soma_digitos = 11 - $soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		$digitos_atualizados = $digitos . $soma_digitos;

		return $digitos_atualizados;
	}
 
	/**
	 * Valida se o campo é um CPF válido
	 * @param  string  $value CPF
	 * @return boolean        Status do processo
	 */
	private function validar_cpf($value)
	{
		$digitos  = substr($value, 0, 9);
		$novo_cpf = $this->calcular_posicoes_dos_digitos($digitos);
		$novo_cpf = $this->calcular_posicoes_dos_digitos($novo_cpf, 11); 

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		return isset($novo_cpf) && $novo_cpf === $value ? true : false;
	}
 
	/**
	 * Valida se o campo é um CNPJ válido
	 * @param  string  $value CPNJ
	 * @return boolean        Status do processo
	 */
	private function validar_cnpj($value)
	{
		$digitos   = substr($value, 0, 12);	
		$novo_cnpj = $this->calcular_posicoes_dos_digitos($digitos, 5);
		$novo_cnpj = $this->calcular_posicoes_dos_digitos($novo_cnpj, 6);
		
 		return isset($novo_cnpj) && $novo_cnpj === $value ? true : false;
	}

	/**
	 * Valida o campo de Cadastro de Pessoa Física ou Jurídica
	 * @param  string  $field  Campo a ser validado
	 * @return boolean     	   Status da validação
	 */
	public function field_cadastropessoa($field, $type = 'CPF')
	{
		$value = preg_replace('/[^0-9]/', '', $this->request[$field]);
		$value = (string) $value;

		$type  = strtoupper($type);

		switch ($type) {
			case 'CNPJ':
				$status = $this->validar_cnpj($value);
				break;
			
			default:
				$status = $this->validar_cpf($value);
				break;
		}
		return $status === true ? $this->valid(true) : $this->setError('cpf-ou-cpnj-invalido', $field);
	}

	/**
	 * Valida se o campo é um username válido
	 * @param  string  $field Campo a ser validado
	 * @return boolean     	  Status da validação
	 */
	public function field_username($field)
	{
		if (preg_match('/^[a-zA-Z0-9]{3,}$/', trim($this->request[$field])))
			return $this->valid(true);

		return $this->setError('username-invalido', $field);
	}

	/**
	 * Valida se o valor do campo é numérico
	 * @param  string  $field Campo a ser validado
	 * @return boolean     	  Status da validação
	 */
	public function field_numeric($field)
	{
		if (is_numeric(trim($this->request[$field])))
			return $this->valid(true);

		return $this->setError('valor-nao-numerico', $field);
	}
	
	/**
	 * Valida se o valor de determinado campo trata-se de um e-mail
	 * @param  string  $field Campo a ser validado
	 * @return boolean     	  Status da validação
	 */
	public function field_email($field)
	{
		if (filter_var($this->request[$field], FILTER_VALIDATE_EMAIL))
			return $this->valid(true);

		return $this->setError('email-invalido', $field);
	}

	/**
	 * Reseta o atributo valid
	 * @param  boolean $status Status temporário
	 * @return boolean         Status definitivo do processo de validação
	 */
	private function valid($status)
	{
		array_push($this->validCheckPoint, $status);
		$this->valid = ! in_array(false, $this->validCheckPoint) ? true : false;

		return $this->valid;
	}

	/**
	 * Armazena os erros
	 * @param  string $field  Nome do campo
	 * @param  integer $error Código do erro
	 * @return boolean   	  Status do processo
	 */
	private function setError($code, $field)
	{
		if ( ! array_key_exists($field, $this->errors) || $this->errors[$field] !== $code) {
			$tmpArray = array($field => $code);
			$this->errors = array_merge_recursive($this->errors, $tmpArray);
		}

		return $this->valid(false);
	}

	/**
	 * Retorna mensagens
	 */
	public function getMessage()
	{
		$total_args = func_num_args();

		if ($total_args == 0)
			return false;

		$args = func_get_args();
		$code = $args[0];

		unset($args[0]);
		$params = empty($args) ? array() : array_values($args);

		return $this->messages->getMessage($code, $params);
	}

	/**
	 * Renderiza as mensagens
	 * @return html
	 */
	private function render()
	{
		$messages = array();
		if ( ! empty($this->errors)) {
			foreach ($this->errors as $field => $code) {
				$message = $this->getMessage($code, $field);
				$messages[$field] = $message['message'];
			}
		}
		return $messages;
	}

	/**
	 * Retorna a mensagem de erro
	 * @return array Array com erros padronizado para o AlertHelper
	 */
	public function getErrors()
	{
		$message = $this->render();

		return !empty($message) ? array(
			$this->style,
			$this->title_error,
			$message
		) : null;
	}
}
