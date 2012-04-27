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
}