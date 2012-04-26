<?php
class Model_Index extends Zend_Db_Table_Abstract
{
	protected $_name = 'admin'; 
	protected $_primary = 'AdminID'; 
	protected $_fields = array('email','password', 'role');
	public function a_fGetList(){
		$o_Select = $this->_db->select();
		echo $o_Select;die;
	}
}