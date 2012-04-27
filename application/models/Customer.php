<?php
class Model_Customer extends Model_Base
{

	/*
     * Search Customer
     */
	public function a_fSearchCustomer ($a_the_Data)
	{
		$o_CustomerTable = new Model_Table_Customer();
		return $o_CustomerTable->a_fSearchCustomer($a_the_Data);
	}

	public function i_fCreateCustomer ()
	{
	}

	public function b_fValidate ($the_a_Params, &$the_o_MessageHandle)
	{
		$o_Caller = new Model_Table_Caller();
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
		
		if (!$sz_FullName || !$sz_Password)
		{
			$the_o_MessageHandle->v_fSetErrorMessage('Please enter required fields !');
			return;
		}
		
		if (isset($a_Destination) && ! empty($a_Destination))
		{
			foreach ($a_Destination as $i_Key => $sz_Destination)
			{
				if ($sz_Destination || $a_SpeedDial[$i_Key])
				{
					if (! is_numeric($sz_Destination))
					{
						$the_o_MessageHandle->v_fSetErrorMessage('Destination'. $i_Key + 1 .' number must be numeric !');
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
						$the_o_MessageHandle->v_fSetErrorMessage('Phone Number '. $i_Key + 1 .' already exists !');
					}
				}
			}
		}
		// Check Firstname + password
		if ($sz_Fund != "")
		{
			if (! is_numeric($fund))
			{
				$the_o_MessageHandle->v_fSetErrorMessage('Add fund must be numeric !');
			}
		}
		
		// Check exists DID
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