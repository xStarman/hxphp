<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->load('Helpers\Menu', $this->request, $this->configs);

		$this->load('Helpers\Alert', array(
			'danger',
			'Título',
			array(
				'Mensagem 1',
				'Mensagem 2'
			)
		));
	}
}