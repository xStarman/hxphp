<?php

class LoginAttempt extends \HXPHP\System\Model
{
	
	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $belongs_to = array(
		array('user')
	);

	/**
	 * Número de tentativas registradas
	 * @var integer
	 */
	public static $attempts;
	
	/**
	 * Retorna a quantidade de tentativas de login mal-sucedidas de determinado usuário
	 * @param integer $user_id ID do usuário
	 */
	static function countAttempts($user_id)
	{
		$now = time();
		$valid_attempts = $now - (2 * 60 * 60);
		
		$attempts = self::find(
			'all',
			array(
				'conditions' => array(
					'user_id = ? AND time > ?',
					$user_id,
					$valid_attempts
				)
			)
		);

		self::$attempts = count($attempts);

		return (int) self::$attempts;
	}

	/**
	 * Verifica se o número de tentativas excedeu o limite de 5
	 * @param integer $user_id ID do usuário
	 */
	static function checkLimitAttempts($user_id)
	{
		self::countAttempts($user_id);
		return self::$attempts > 5 ? true : false;
	}

	/**
	 * Registra uma tentativa de login mal-sucedida para determinado usuário
	 * @param integer $user_id ID do usuário
	 */
	static function registerAttempt($user_id)
	{
		return self::create(array(
				'user_id' => $user_id,
				'IP' => $_SERVER['REMOTE_ADDR']
			)
		);
	}

	/**
	 * Exclui todas as tentativas após login bem sucedido, caso o usuário não seja bloqueado
	 * @param integer $user_id ID do usuário
	 */
	static function deleteAttempts($user_id)
	{
		self::delete_all(
			array(
				'conditions' => array(
					'user_id = ?',
					$user_id
				)
			)
		);
	}
}