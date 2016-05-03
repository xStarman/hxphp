<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->load('Modules\Messages', 'auth');
		$this->messages->setBlock('alerts');

		$alert = $this->messages->getByCode('conta-em-uso');

		$this->load('Helpers\Alert', $alert);
	}
}