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

		$this->load('Services\Auth');

		var_dump($this->auth->messages->getByCode('conta-em-uso', array(
			'message' => 'ola'
		)));
	}
}