<?php 

class Error404Controller extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->view->setPath('index')
				   ->setVar('ola', 'Mundo')
				   ->setAssets('css', 'teste.css')
				   ->setTitle('Oops! Nada encontrado!');
	}
}