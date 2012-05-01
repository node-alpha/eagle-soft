<?php
class Model_TariffGroup extends Model_Base
{	
	/**
	 * Get tariff group assoc for edit account
	 * Enter description here ...
	 */
	public function a_fGetTariffGroup()
	{
		$o_TariffGroupTable = new Model_Table_TariffGroup();
		return $o_TariffGroupTable->a_fGetTariffGroup();
	}
}