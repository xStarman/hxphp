<?php 

class IndexController extends \HXPHP\System\Controller
{
	public function indexAction()
	{
		$this->load('Helpers\Menu', $this->request, $this->configs);

		$this->load('Services\Email');
		$this->email->setFrom($this->configs->mail->getFrom());

		print_r($this->email->send(
			'bruno@hxphp.com.br',
			'Hello World!',
			'Mensagem <strong>com HTML</strong>'
		));
	}
}