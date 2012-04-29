<?php
class Model_Table_Caller extends Model_Table_Base
{
	protected $_name = 'cc_callerid';
	protected $_primary = 'id';

	/*
	 * Update Customer
	 * return int 
	 */
	public function b_fCheckExist ($the_a_Data)
	{
		$o_Select = $this->_db->select()->from(array($this->_name), 'id');
		foreach ($the_a_Data as $sz_Key => $sz_Value) {
			$o_Select->where($sz_Key . '=?', $sz_Value);
		}
		return $this->_db->fetchOne($o_Select);
	}
	
	/**
	 * Insert caller
	 */
	public function i_fInsert($the_a_Data)
	{
		return $this->insert($the_a_Data);
	}
	
	/**
	 * Get caller of card
	 * Enter description here ...
	 * @param unknown_type $the_i_cardId
	 */
	public function a_fGetCaller($the_i_cardId = 0)
	{
		$o_Select = $this->_db->select()->from($this->_name, array('cid'))->where('id_cc_card = ?', $the_i_cardId);
		return $this->_db->fetchCol($o_Select);
	}
}