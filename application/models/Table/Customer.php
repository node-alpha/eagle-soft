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
        $o_Select = $this->_db->select()->from($this->_name);
        foreach ($the_a_Data as $sz_Key => $sz_Value) {
            $o_Select->where($sz_Key . '=?', $sz_Value);
        }
        return $this->_db->fetchAll($o_Select);
    }
    /*
	 * Create Customer
	 * return int 
	 */
    public function i_fInsert ($the_a_Data)
    {
        return $this->insert($the_a_Data);
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