<?php

class LostPassword extends Model
{
	
	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $belongs_to = array(
		array('user')
	);

	/**
	 * Envia a mensagem de redefinição com o link e token de permissão
	 * @param  string $username Username do usuário
	 * @return array            Mensagem resultante do processo
	 */
	static function recovery($username)
	{
		$PasswordRecovery = new PasswordRecovery;

		$user = User::find_by_username($username);
		if (is_null($user))
			return $PasswordRecovery->getMessage('nenhum-usuario-encontrado');

		$PasswordRecovery->setLink(SITE.'esqueci-a-senha/redefinir/');
		$callback_message = $PasswordRecovery->sendRecoveryLink($user->full_name, $user->email);

		if ($PasswordRecovery->status === true){
			self::delete_all(
				array(
					'conditions' => array(
						'user_id=?',
						$user->id
					)
				)
			);

			self::create(
				array(
					'user_id' => $user->id,
					'IP'      => $_SERVER['REMOTE_ADDR'],
					'token'   => $PasswordRecovery->token,
					'status'  => 0
				)
			);
		}

		return $callback_message;
	}

	/**
	 * Verifica o token e atualizar a senha do usuário
	 * @param  string $token       Código alfanumérico de permissão
	 * @param  string $newPassword Nova senha do usuário
	 * @return array               Mensagem resultante do processo
	 */
	static function confirm($token, $newPassword)
	{
		$PasswordRecovery = new PasswordRecovery;
		$lostRegister = LostPassword::find_by_token_and_status($token, 0);

		if (is_null($lostRegister))
			return $PasswordRecovery->getMessage('token-invalido');
	
		$user = User::find($lostRegister->user_id);
		if (is_null($user))
			return $PasswordRecovery->getMessage('nenhum-usuario-encontrado');

		$credenciais = Tools::hashHX($newPassword);
		$user->update_attributes(
			array(
				'salt' => $credenciais['salt'],
				'password' => $credenciais['password']
			)
		);

		$lostRegister->delete();

		return $PasswordRecovery->getMessage('senha-redefinida');
	}
}