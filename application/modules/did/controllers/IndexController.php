<?php

/**
 * Customer controller
 *
 */
class Did_IndexController extends Core_Controller_Action
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
		$o_MessageHandler = $this->b_fValidate();
		if($o_MessageHandler->b_fHasError())
		{
			echo $o_MessageHandler;
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			return;
		}
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
	
	/**
	 * Get customer to edit
	 * Enter description here ...
	 */
	public function getCustomerAction()
	{		
		$i_CustId = $this->_request->getParam('id');
		$o_CustomerModel = new Model_Customer();
		$a_Customer = $o_CustomerModel->a_fGetCustomer($i_CustId);
		$this->view->a_Customer = $a_Customer;
		$o_CountryModel = new Model_Country();
		$this->view->assign('a_Countries', $o_CountryModel->a_fGetListCountry());
		$this->view->assign('a_Invoiceday', range(1, 31));
		$o_TariffGroupModel = new Model_TariffGroup();
		$o_TariffGroup = $o_TariffGroupModel->a_fGetTariffGroup();
		foreach($o_TariffGroup as $tariff)
		{			
			$a_TariffGroup[$tariff->id] = $tariff->tariffgroupname;	
		}
		
		$o_CallerTable = new Model_Table_Caller();
		$this->view->a_Callers = $o_CallerTable->a_fGetCaller($i_CustId);
		$o_DialTable = new Model_Table_SpeedDial();
		$this->view->a_Dials = $o_DialTable->a_fGetDial($i_CustId);
		$a_Status = array(
			0 => 'CANCELED',
			1 => 'ACTIVE',
			2 => 'NEW',
			3 => 'WAITING-MAILCONFIRMATION',
			4 => 'RESERVED',
			5 => 'EXPIRED',
			6 => 'SUSPENDED FOR UNDERPAYMENT',
			7 => 'SUSPENDED FOR LITIGATION',
			8 => 'WAITING SUBSCRIPTION PAYMENT'
		);
		$this->view->assign('a_Status', $a_Status);
		$a_Access = array(
			0 => 'INDIVIDUAL ACCESS',
			1 => 'SIMULTANEOUS ACCESS'
		);
		$this->view->assign('a_SimulAccess', $a_Access);
		$a_LimitNotification = array(
			-1 => 'NOT DEFINED',
			10 => 10,
			20 => 20,
			50 => 50,
			100 => 100,
			500 => 500,
			1000 => 1000,
		);
		$this->view->assign('a_LimitNotification', $a_LimitNotification);
		$this->view->assign('tariffgroup', $a_TariffGroup);
		$this->_helper->layout->disableLayout();
	}
	
	public function editAction()
	{	
		$o_MessageHandler = $this->b_fValidate();
		if($o_MessageHandler->b_fHasError())
		{
			echo $o_MessageHandler;
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			return;
		}
		$o_CustomerModel = new Model_Customer();
		$a_Params = $this->_request->getParams();
		$i_Id = $this->_request->getParam('id');
		$o_CustomerModel->i_fUpdateCard($a_Params, $i_Id);
		if (! $o_MessageHandler->b_fIsError())
		{
			$o_MessageHandler->v_fSetSuccess();
			$o_MessageHandler->v_fSetMessage('Edit Customer successful !');
		}
		echo $o_MessageHandler;
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
	}
}
?>