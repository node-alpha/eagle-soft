<?php
class Model_Country extends Model_Base
{

	public function a_fGetListCountry ()
	{
		$o_CountryTable = new Model_Table_Country();
		$a_Countries = $o_CountryTable->a_fGetAllCountry();
		$a_ListCountry = array();
		foreach ($a_Countries as $o_Country)
		{
			$a_ListCountry[$o_Country->countrycode] = $o_Country->countryname;
		}
		return $a_ListCountry;
	}
	
	public function a_fGetListState ()
	{
		$o_CountryTable = new Model_Table_Country();
		$a_State = $o_CountryTable->a_fGetAllState();
		$a_ListState = array();
		foreach ($a_State as $o_State)
		{
			$a_ListState[$o_State->area_code] = $o_State->area_name;
		}
		return $a_ListState;
	}
}