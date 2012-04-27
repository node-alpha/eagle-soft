<?php
class Model_Table_SpeedDial extends Zend_Db_Table_Abstract
{
	protected $_name = 'cc_speeddial';
	protected $_primary = 'id';

	public function i_fInsert ($the_a_Data)
	{
		return $this->insert($the_a_Data);
	}
}
?>