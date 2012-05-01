<?php

/**
 * Customer controller
 *
 */
class Did_OutboundController extends Core_Controller_Action
{		
public function indexAction()
	{		
		$this->view->headScript()->appendFile($this->sz_fGetPublicHost() . 'js/modules/outbound.js', 'text/javascript');
		$this->view->headTitle('Add CID');
		$o_GroupModel = new Model_OutboundGroup();
		$a_Groups = $o_GroupModel->a_fSearch(array('groupname' => ''));
		$a_GroupAs[''] = 'Please Select';
		foreach((array)$a_Groups as $o_Group)
		{
			$a_GroupAs[$o_Group->id] = $o_Group->group_name;  	
		}
		$this->view->a_GroupAs = $a_GroupAs;
	}
	
	public function addAction()
	{	
		$o_MessageHandler = $this->b_fValidate();
		if($o_MessageHandler->b_fHasError())
		{
			echo $o_MessageHandler;
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			exit();
		}
		$o_Model = new Model_Outbound();
		$a_Params = $this->_request->getParams();
		$o_Model->i_fInsert($a_Params);
		$o_MessageHandler->v_fSetMessage('Create CID successful !');
		echo $o_MessageHandler;
		die();
	}
	
	public function searchAction()
	{
		$a_Params = $this->_request->getParams();
		$o_Model = new Model_Outbound();
		$a_ListItems = $o_Model->a_fSearch($a_Params);
		$this->view->a_ListItems = $a_ListItems;
		$this->_helper->layout->disableLayout();
	}
	
	public function deleteAction()
	{		
		$o_MessageHandler = new Core_MessageHandle();
		$o_Model = new Model_Outbound();
		$i_Id = $this->_request->getParam('id');
		$o_Model->i_fDelete($i_Id);
		$o_MessageHandler->addMessage('Delete CID successfull !');
		echo $o_MessageHandler;
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
	}
	
	public function editAction()
	{
		$o_Model = new Model_Outbound();
		$i_Id = $this->_request->getParam('id');
		$this->view->o_Item = $o_Model->o_fGetItem($i_Id);
		$o_GroupModel = new Model_OutboundGroup();
		$a_Groups = $o_GroupModel->a_fSearch(array('groupname' => ''));
		$a_GroupAs[''] = 'Please Select';
		foreach((array)$a_Groups as $o_Group)
		{
			$a_GroupAs[$o_Group->id] = $o_Group->group_name;  	
		}
		$this->view->a_GroupAs = $a_GroupAs;
		$this->_helper->layout->disableLayout();
	}
	
	public function saveAction()
	{		
		$o_MessageHandler = $this->b_fValidate();
		if($o_MessageHandler->b_fHasError())
		{
			echo $o_MessageHandler;
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			exit();
		}
		$o_Model = new Model_Outbound();
		$a_Params = $this->_request->getParams();
		$i_Id = $this->_request->getParam('id');
		$o_Model->i_fUpdate($a_Params, $i_Id);
		$o_MessageHandler->v_fSetMessage('Update CID successful !');
		echo $o_MessageHandler;
		die();
	}
}
?>