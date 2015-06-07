<?php 

class Error404Controller extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load('Auth');
	}
	public function indexAction()
	{
		//Define o título da página
		$this->view->setTitle('Oops! Nada encontrado!');
		
		if ($this->auth->login_check()) {
			$this->view->setMenu(User::userActive()->role);
			$this->render('404');
		}

		$this->render('404', '', true, 'Generic');
	}
}