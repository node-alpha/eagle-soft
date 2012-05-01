<?php
class Model_Table_TariffGroup extends Model_Table_Base
{
	protected $_name = 'cc_tariffgroup';
	protected $_primary = 'id';
	
	/**
	 * Get tariff group assoc for edit account
	 * Enter description here ...
	 */
	public function a_fGetTariffGroup()
	{
		$o_Select = $this->_db->select()->from($this->_name, array('id','tariffgroupname'));
		return $this->_db->fetchAll($o_Select);
	}
}