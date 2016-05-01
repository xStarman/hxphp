<?php 

class HaveFunController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->load(
			'Helpers\Menu',
			$this->request,
			$this->configs
		);

		$this->view->setVar('menu', $this->menu->getMenu());
	}
}