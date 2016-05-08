<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->load(
			'Services\Auth',
			$this->configs->auth->after_login,
			$this->configs->auth->after_logout,
			false,
			$this->request->subfolder
		);
		$this->auth->login(3, 'teste');
		var_dump($this->auth->login_check());

		$this->view->setPath('admin/havefun');
	}
}