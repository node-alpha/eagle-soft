<?php
class Model_Table_Country extends Zend_Db_Table_Abstract
{
	protected $_name = 'cc_country';
	protected $_primary = 'id';

	public function a_fGetAllCountry ()
	{
		$o_Select = $this->_db->select()->from(array($this->_name), array('*'))->order('countryname ASC');
		return (array)$this->_db->fetchAll($o_Select);
	}
	
	public function a_fGetAllState ()
	{
		$o_Select = $this->_db->select()->from(array('tblusarea'), array('*'))->order('area_name ASC');
		return (array)$this->_db->fetchAll($o_Select);
	}
}
?>