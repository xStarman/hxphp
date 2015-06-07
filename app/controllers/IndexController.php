<?php 

class IndexController extends Controller
{
    public function indexAction()
    {
    	$this->view->setTitle('Seja bem vindo');

        $this->render('Index', '', true, 'Generic', '', array(
        	CSS.'animate.css'
        ));
    }
}