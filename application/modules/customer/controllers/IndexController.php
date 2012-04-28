<?php

/**
 * Customer controller
 *
 */
class Customer_IndexController extends Core_Controller_Action
{		
	public function indexAction ()
	{	
		$o_CountryModel = new Model_Country();
		$this->view->headScript()->appendFile($this->sz_fGetPublicHost() . 'js/modules/customer.js', 'text/javascript');
		$this->view->headScript()->appendFile($this->sz_fGetPublicHost() . 'js/jquery.multiFieldExtender-2.0.js', 'text/javascript');
		$this->view->headTitle('Customer Account');
		$this->view->assign('a_Countries', $o_CountryModel->a_fGetListCountry());
	}

	public function createAction ()
	{
		$o_MessageHandle = new Core_MessageHandle();
		$o_CustomerModel = new Model_Customer();
		$a_Params = $this->v_fClean($this->_request->getParams());
		$b_Valid = $o_CustomerModel->b_fValidate($a_Params, $o_MessageHandle);
		if ($b_Valid)
		{
			// Insert card
			$i_CardId = $o_CustomerModel->i_fInsertCard($a_Params);
			if ($i_CardId)
			{
				// Insert Caller
				foreach ($a_Params['phone'] as $sz_Phone)
				{
					if ($sz_Phone)
					{
						// Insert phone
						if (substr($sz_Phone, 0, 1) == "+")
						{
							$sz_Phone = substr($sz_Phone, 1);
						}
						if (substr($sz_Phone, 0, 2) == "00")
						{
							$sz_Phone = substr($sz_Phone, 2);
						}
						$a_DataPhone = array('cid' => $sz_Phone , 'id_cc_card' => $i_CardId , 'creationdate' => 'NOW()' , 'activated' => 't');
						$o_CallerTable = new Model_Table_Caller();
						$o_CallerTable->i_fInsert($a_DataPhone);
					}
				}
				// Insert Speed dial
				foreach ($a_Params['spd_dial'] as $i_Key => $sz_Dial)
				{
					if ($sz_Dial == 'Name')
					{
						$sz_Dial = "";
					}
					if ($a_Params['dest'][$i_Key] == 'Destination')
					{
						$a_Params['dest'][$i_Key] = "";
					}
					$a_Dial = array('speeddial' => $i_Key + 1 , 'name' => $sz_Dial , 'phone' => $a_Params['dest'][$i_Key] , 'creationdate' => 'Now()' , 'id_cc_card' => $i_CardId);
					$o_SpeedDialTable = new Model_Table_SpeedDial();
					$o_SpeedDialTable->i_fInsert($a_Dial);
				}
			}
		}
		if (! $o_MessageHandle->b_fIsError())
		{
			$o_MessageHandle->v_fSetSuccess();
			$o_MessageHandle->v_fSetMessage('Create Customer successful !');
		}
		echo $o_MessageHandle;
		die();
	}

	public function searchAction ()
	{
		$a_Params = $this->v_fClean($this->_request->getParams());
		$a_DataSearch = array(
						'id' => $a_Params['search_account_id'],
						'email' => $a_Params['search_email'],
						'wholesale_orig_ip' => $a_Params['search_ip'],
						'phone' => $a_Params['search_batch_number'],
						'firstname' => $a_Params['search_batch_name'],
						'cid' => $a_Params['search_callerid'],
						);
		$o_CustomerModel = new Model_Customer();
		$a_Response = $o_CustomerModel->a_fSearchCard($a_DataSearch);
		$this->view->assign('a_Response', $a_Response);
		$this->_helper->layout->disableLayout();
	}
	
	public function loadStateAction()
	{
		$a_State = array();
		if ($this->_request->getParam('country') == 'USA')
		{
			$o_CountryModel = new Model_Country();
			$a_State = $o_CountryModel->a_fGetListState();
		}
		$this->view->assign('a_State', $a_State);
		$this->_helper->layout->disableLayout();
	}
}
?>