<?php
class Model_Table_Contact extends Zend_Db_Table_Abstract
{
	protected $_name = 'dd_contacts';
	protected $_primary = 'dd_contact_id';

	public function b_fCheckExist ()
	{
		$o_Select = $this->_db->select()->from(array($this->_name), 'dd_contact_id');
		foreach ($the_a_Data as $sz_Key => $sz_Value)
		{
			$o_Select->where($sz_Key . '=?', $sz_Value);
		}
		return $this->_db->fetchOne($o_Select);
	}
}
?>