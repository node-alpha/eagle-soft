<?php
class IndexController extends Zend_Controller_Action
{
	public function indexAction(){
		$o_IndexModel = new Model_Customer_Index();
		$a_Customer = $o_IndexModel->a_fGetList();
		var_dump($a_Customer);
	}
}