<?php
class Model_Customer extends Model_Base
{

	/*
     * Search Customer
     */
	public function a_fSearchCard ($the_a_Data)
	{
		$o_CustomerTable = new Model_Table_Customer();
		return $o_CustomerTable->a_fSearchCard($the_a_Data);
	}

	public function i_fInsertCard ($the_a_Data)
	{
		$a_Data = array('id' => NULL , 'creationdate' => 'NOW()' , 'firstusedate' => '0000-00-00 00:00:00' , 'expirationdate' => 'DATE_ADD(NOW(), INTERVAL 10 YEAR)' , 'enableexpire' => 0 , 'expiredays' => 0 , 'username' => uniqid() , 'useralias' => uniqid() , 'uipass' => $the_a_Data['password'] , 'type' => 'W' , 'credit' => $the_a_Data['fund'] , 'tariff' => 1 , 'id_didgroup' => - 1 , 'activated' => 't' , 'status' => 1 , 'lastname' => '' , 'firstname' => $the_a_Data['firstname'] , 'address' => '' , 'city' => '' , 'state' => '' , 'country' => $the_a_Data['country'] , 'zipcode' => '' , 'phone' => $the_a_Data['phone'][0] , 'email' => $the_a_Data['email'] , 'fax' => '' , 'inuse' => 0 , 'simultaccess' => 1 , 'currency' => 'USD' , 'lastuse' => 'NOW()' , 'nbused' => 3 , 'typepaid' => 0 , 'creditlimit' => 0 , 'voipcall' => 0 , 'sip_buddy' => 1 , 'iax_buddy' => 1 , 'language' => 'en' , 'redial' => '' , 'runservice' => 0 , 'nbservice' => 0 , 'id_campaign' => - 1 , 'num_trials_done' => 0 , 'vat' => 0 , 'servicelastrun' => '0000-00-00 00:00:00' , 'initialbalance' => 0.00000 , 'invoiceday' => 1 , 'autorefill' => 0 , 'loginkey' => '' , 'mac_addr' => '00-00-00-00-00-00' , 'id_timezone' => 49 , 'tag' => '' , 'voicemail_permitted' => 0 , 'voicemail_activated' => 0 , 'last_notification' => NULL , 'email_notification' => '' , 'notify_email' => 0 , 'credit_notification' => - 1 , 'id_group' => 1 , 'company_name' => '' , 'company_website' => '' , 'vat_rn' => NULL , 'traffic' => NULL , 'traffic_target' => '' , 'discount' => 0.00 , 'restriction' => 0 , 'id_seria' => - 1 , 'serial' => NULL , 'block' => 0 , 'lock_pin' => '0' , 'lock_date' => NULL , 'refills_overal' => 0.00000 , 'bonus_get' => 0.00000 , 'refills_cleared' => 0.00000 , 'reseller_id' => '');
		$o_CustomerTable = new Model_Table_Customer();
		return $o_CustomerTable->i_fInsert($a_Data);
	}

	public function b_fValidate ($the_a_Params, &$the_o_MessageHandle)
	{
		$o_Caller = new Model_Table_Caller();
		$o_Customer = new Model_Table_Customer();
		$a_Destination = $the_a_Params['dest'];
		$a_SpeedDial = $the_a_Params['spd_dial'];
		$a_Phone = $the_a_Params['phone'];
		$sz_FullName = $the_a_Params['firstname'];
		$sz_Password = $the_a_Params['password'];
		$sz_Email = $the_a_Params['email'];
		$sz_Country = $the_a_Params['country'];
		$sz_Fund = $the_a_Params['fund'];
		$sz_DirectDial = $the_a_Params['direct_dial'];
		// Validate Destination
		if (! $sz_FullName || ! $sz_Password)
		{
			$the_o_MessageHandle->v_fSetErrorMessage('Please enter required fields !');
			return false;
		}
		if ($o_Customer->b_fCheckExist(array('email' => $sz_Email)))
		{
			$the_o_MessageHandle->v_fSetErrorMessage('Email already exists !');
		}
		if (isset($a_Destination) && ! empty($a_Destination))
		{
			foreach ($a_Destination as $i_Key => $sz_Destination)
			{
				if ($sz_Destination || $a_SpeedDial[$i_Key])
				{
					if (! is_numeric($sz_Destination))
					{
						$the_o_MessageHandle->v_fSetErrorMessage('Destination' . ($i_Key + 1) . ' number must be numeric !');
						break;
					}
				}
			}
		}
		// Check phone exists
		if (isset($a_Phone) && ! empty($a_Phone))
		{
			foreach ($a_Phone as $i_Key => $sz_Phone)
			{
				if (! is_numeric($sz_Phone))
				{
					$the_o_MessageHandle->v_fSetErrorMessage('Caller ID must be numeric !');
				} else
				{
					if ($o_Caller->b_fCheckExist(array('cid' => $sz_Phone)))
					{
						$the_o_MessageHandle->v_fSetErrorMessage('Caller ID #' . ($i_Key + 1) . ' already exists !');
					}
				}
			}
		}
		// Check Firstname + password
		if ($sz_Fund != "")
		{
			if (! is_numeric($sz_Fund))
			{
				$the_o_MessageHandle->v_fSetErrorMessage('Add fund must be numeric !');
			}
		}
		return $the_o_MessageHandle->b_fIsError() ? false : true;
	}

	public function gen_sipid ()
	{
		$pwd = "";
		$chrs = 7;
		mt_srand((double) microtime() * 1000000);
		$i = 0;
		while (strlen($pwd) < $chrs)
		{
			$i ++;
			$chr = chr(mt_rand(0, 255));
			if (eregi("^[0-9]$", $chr))
			{
				$pwd = $pwd . $chr;
			}
			if ($i == 1 && $pwd == 0)
			{
				$pwd = "";
				$i = 0;
			}
		}
		return '999' . $pwd;
	}

	public function gen_username ()
	{
		$pwd = "";
		$chrs = 10;
		mt_srand((double) microtime() * 1000000);
		$i = 0;
		while (strlen($pwd) < $chrs)
		{
			$i ++;
			$chr = chr(mt_rand(0, 255));
			if (eregi("^[0-9]$", $chr))
			{
				$pwd = $pwd . $chr;
			}
			if ($i == 1 && $pwd == 0)
			{
				$pwd = "";
				$i = 0;
			}
		}
		return $pwd;
	}

	public function v_fGenerateUser ()
	{
		for ($i = 0; $i < 2000; $i ++)
		{
			$username = gen_username();
			$query = mysql_query("SELECT username FROM cc_card WHERE username = '$username'", $connA);
			if (mysql_num_rows($query) > 0)
			{
				continue;
			} else
			{
				break;
			}
		}
		for ($i = 0; $i < 2000; $i ++)
		{
			$sipid = gen_sipid();
			$query = mysql_query("SELECT name FROM cc_sip_buddies WHERE name LIKE '$sipid'", $connA);
			if (mysql_num_rows($query) > 0)
			{
				continue;
			} else
			{
				break;
			}
		}
	}
}