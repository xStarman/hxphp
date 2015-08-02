<?php

class Project extends \HXPHP\System\Model
{

	/**
	 * Configuração para a associação entre tabelas
	 * @var array
	 */
	static $belongs_to = array(
		array('user')
	);

	/**
	 * Retorna um Array tratado para o PFBC
	 * @return array Array tratado para o PFBC
	 */
	static function getOptions()
	{
		$projects = self::all();
		$options = array(
			'' => 'Selecione...'
		);

		foreach ($projects as $project) {
			$options[$project->id] = $project->nome;
		}

		return $options;
	}
}