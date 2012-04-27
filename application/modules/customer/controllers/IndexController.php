<?php
/**
 * Customer controller
 *
 */
class Customer_IndexController extends BaseController
{

	/**
	 * Default action
	 * @return void
	 */
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
		$o_MessageHandle = new Model_MessageHandle();
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
					if ($sz_Dial)
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
			}
		}
	}

	public function searchAction ()
	{
		// ajax Request
		$this->_diableView = true;
	}
}
?>