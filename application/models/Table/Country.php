<?php
class Model_Table_Country extends Zend_Db_Table_Abstract
{
	protected $_name = 'cc_country';

	public function a_fGetAllCountry ()
	{
		$this->_db->select()->from(array($this->_name), array('*'))->order('countryname ASC');
		return $this->_db->fetchAll();
	}
}
?>