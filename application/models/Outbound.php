<?php
class Model_Outbound extends Model_Base
{
	/*
	 * Search Customer
	 */
	public function a_fSearch($the_a_Data)
	{
		$o_Table = new Model_Table_Outbound();
		return $o_Table->a_fSearch($the_a_Data);
	}

	public function i_fInsert($the_a_Data)
	{
		$a_Data = array(
			'outbound_cid_group' => $the_a_Data['group'],
			'cid' => $the_a_Data['cid'],
			'activated' => $the_a_Data['active'],
			'creationdate' => date('Y-m-d H:i:s')
		);
		$o_Table = new Model_Table_Outbound();
		return $o_Table->i_fInsert($a_Data);
	}	

	public function i_fUpdate($the_a_Data, $the_i_Id)
	{
		$a_Data = array(
			'outbound_cid_group' => $the_a_Data['group'],
			'cid' => $the_a_Data['cid'],
			'activated' => $the_a_Data['active'],
		);
		$o_Table = new Model_Table_Outbound();
		return $o_Table->i_fUpdate($a_Data, $the_i_Id);
	}
	
	public function i_fDelete($the_i_Id)
	{
		$o_Table = new Model_Table_Outbound();
		return $o_Table->i_fDelete($the_i_Id);
	}
	
	public function o_fGetItem($the_i_Id)
	{
		$o_Table = new Model_Table_Outbound();
		return $o_Table->o_fGetItem($the_i_Id);
	}
}