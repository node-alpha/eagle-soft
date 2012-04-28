<?php
class Model_Table_Customer extends Model_Table_Base
{
	protected $_name = 'cc_card';
	protected $_primary = 'id';

	/*
	 * Search Customer
	 * @return Array
	 */
	public function a_fSearchCard ($the_a_Data)
	{
		$o_Select = $this->_db->select()->from(array('card' => $this->_name));
		foreach ($the_a_Data as $sz_Key => $sz_Value)
		{
			if ($sz_Value)
			{
				if ($sz_Key == 'cid')
				{
					$o_Select->join(array('call' => 'cc_callerid'),'card.id = call.id_cc_card','cid');
					$o_Select->Where('LOWER(call.' .$sz_Key . ') like LOWER("%'. $sz_Value .'%")');
				}
				else
				{
					$o_Select->Where('LOWER(card.' .$sz_Key . ') like LOWER("%'. $sz_Value .'%")');
				}
			}
		}
		return $this->_db->fetchAll($o_Select);
	}

	/*
	 * Update Customer
	 * return int 
	 */
	public function b_fCheckExist ($the_a_Data)
	{
		$o_Select = $this->_db->select()->from(array($this->_name), 'id');
		foreach ($the_a_Data as $sz_Key => $sz_Value)
		{
			$o_Select->where($sz_Key . '=?', $sz_Value);
		}
		return $this->_db->fetchOne($o_Select);
	}

	/*
	 * Create Customer
	 * return int 
	 */
	public function i_fInsert ($the_a_Data)
	{
		$id = $this->insert($the_a_Data);
		if ($id)
		{
			$password = $the_a_Data['password'];
			$username = uniqid();
			$sql = "INSERT INTO cc_sip_buddies (id, id_cc_card, name, accountcode, regexten, amaflags, callgroup, callerid, 
			canreinvite, context, DEFAULTip, dtmfmode, fromuser, fromdomain, host, insecure, language, mailbox, md5secret, 
			nat, deny, permit, mask, pickupgroup, port, qualify, restrictcid, rtptimeout, rtpholdtimeout, secret, type, username, disallow, allow, 
			musiconhold, regseconds, ipaddr, cancallforward, fullcontact, setvar, regserver, lastms, defaultuser, auth, subscribemwi, vmexten, 
			cid_number, callingpres, usereqphone, incominglimit, subscribecontext, musicclass, mohsuggest, allowtransfer, autoframing, 
			maxcallbitrate, rtpkeepalive, useragent) VALUES
			(NULL, '$id', '$sipid', $username, '$sipid', 'billing', NULL, '', 'no', 'ctx-sip', NULL, 'RFC2833', '', '', 'dynamic', '', 
			NULL, '$sipid', '', 'yes', '', NULL, '', NULL, '', 'no', NULL, NULL, NULL, $password, 'friend', '$sipid', 'ALL', 'ulaw,g729', '', 0, '', 
			'yes', '', '', NULL, '0', '$sipid', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '')";
			//			$stmt = $this->getAdapter()->prepare($sql);
		//			$stmt->execute(); 
		}
		return $id;
	}

	/*
	 * Update Customer
	 * return int 
	 */
	public function i_fUpdate ($the_a_Data, $the_sz_Where)
	{
		return $this->update($the_a_Data, $the_sz_Where);
	}
}