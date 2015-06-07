<?php

class User extends Model
{

	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $belongs_to = array(
		array('state')
	);

	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $has_many = array(
	 	array('projects'),
	 	array('login_attempts'),
		array('lost_passwords')
	);

	/**
	 * Retorna um array com as IDs e nomes dos usuários
	 * @return array Array tratado para o PFBC
	 */
	static function getOptions()
	{
		$users = self::all();
		$options = array(
			'' => 'Selecione...'
		);

		foreach ($users as $user)
			$options[$user->id] = $user->full_name;

		return $options;
	}

	/**
	 * Autentica o usuário
	 * @param  string $username Nome de usuário para a autenticação
	 * @param  string $password Senha do usuário para a autenticação
	 * @return mixed          	Retorna o status da autenticação
	 */
	static function login($username, $password)
	{
		$user = self::find_by_username($username);

		/**
		 * Serviço de Autenticação
		 * @var object
		 */
		$auth = new Auth;
		if (!is_null($user)) {

			$password = Tools::hashHX($password, $user->salt);
			$password = $password['password'];

			if ($user->status == USER_STATUS_LOCKED) {
				return $auth->getMessage('usuario-bloqueado');
			}
			else {
				if (LoginAttempt::checkLimitAttempts($user->id) === false) { 
					if ($user->password === $password) {
						LoginAttempt::deleteAttempts($user->id);
						
						$user->update_attributes(array('status' => 1));

						return $auth->login($user->id, $username);
		          	}
		          	else {
		          		LoginAttempt::registerAttempt($user->id);

		          		if (LoginAttempt::countAttempts($user->id) > 2)
		          			return $auth->getMessage('tentativas-esgotando',(7-LoginAttempt::$attempts));

			            return $auth->getMessage('dados-incorretos');
		          	}
		        }
		        else {
		        	self::lockUser($user->id);
		        	LoginAttempt::deleteAttempts($user->id);

					return $auth->getMessage('usuario-bloqueado',$user->full_name);
				}
			}
		}
		else return $auth->getMessage('usuario-inexistente');
	}

	/**
	 * Bloqueia o usuário por múltipla tentativa
	 * @param  integer $user_id ID do usuário
	 */
	static function lockUser($user_id)
	{
		$user = self::find($user_id);

		if (!is_null($user)) {
			$user->update_attributes(array(
				'status' => USER_STATUS_LOCKED
			));
		}
	}

	/**
	 * Retorna o objeto do usuário autenticado
	 * @return object Objeto do usuário logado
	 */
	static function userActive()
	{
		$auth = new Auth;
		return self::find_by_id($auth->getUserId());
	}

	/**
	 * Valida a permissão de acesso para o nível do usuário atual
	 * @param  array|string  $roles	 String ou Array com role(s) permitida(s)
	 */
	static function roleCheck($roles)
	{
		$auth = new Auth;
		$status = array(false);

		if (is_array($roles) && !empty($roles)) {
			foreach ($roles as $role) {
				if (self::userActive()->role == $role)
					array_push($status, true);
			}
		}
		elseif (!empty($roles)) {
			if (self::userActive()->role == $roles)
				array_push($status, true);
		}
		
		if ( ! in_array(true, $status))
			$auth->response->redirectTo(URL_REDIRECT_AFTER_LOGIN);
	}
}