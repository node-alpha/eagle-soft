<?php
class Model_Table_SpeedDial extends Zend_Db_Table_Abstract
{
	protected $_name = 'cc_speeddial';
	protected $_primary = 'id';

	public function i_fInsert ($the_a_Data)
	{
		return $this->insert($the_a_Data);
	}
	
	/**
	 * Get speeddial of card
	 * Enter description here ...
	 * @param unknown_type $the_i_cardId
	 */
	public function a_fGetDial($the_i_cardId = 0)
	{
		$o_Select = $this->_db->select()->from($this->_name, array('name','phone'))->where('id_cc_card = ?', $the_i_cardId);
		return $this->_db->fetchAll($o_Select);
	}
}
?>