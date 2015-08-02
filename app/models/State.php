<?php

class State extends \HXPHP\System\Model
{

	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $has_many = array(
	 	array('users')
	);

	/**
	 * Retorna um array com as IDs e siglas dos estados
	 * @return array Array tratado para o PFBC
	 */
	static function getSelectStates()
	{
		$all = self::all();
		$statesFiltered = array(
			'' => 'Selecione...'
		);

		foreach($all as $state)
			$statesFiltered[$state->id] = $state->sigla;

		return $statesFiltered;
	}
}