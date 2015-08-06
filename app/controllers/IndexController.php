<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		/*
		 	//Opções disponíveis
			$this->view->setPath('index')
					   ->setFile('index')
					   ->setHeader('Header')
					   ->setFooter('Footer')
					   ->setTemplate(true)
					   ->setVar('ola', 'Mundo')
					   ->setVars(array(
					   		'teste' => 'Ola Mundo'
					   	))
					   ->setAssets('css', 'teste.css')
					   ->setAssets('css', array(
					   		'teste2.css',
					   		'teste3.css'
					   	))
					   ->setAssets('js', array(
					   		'teste2.js',
					   		'teste3.js'
					   	))
					   ->setTitle('Oops! Nada encontrado!');
		*/
	}
	public function testeAction()
	{
		$this->view->setFile('index');

		/**
		 * Carrega o módulo de mensagens com o template auth.json
		 */
		$this->load('Modules\Messages', array(
			'auth'
		));
	}
}