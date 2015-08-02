<?php 

class IndexController extends \HXPHP\System\Controller
{
    public function indexAction()
    {
    	$this->view->setTitle('Seja bem vindo');
        $this->render('Index');
    }
}