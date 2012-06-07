<?php
if($_VPS !== true) { die("x"); }

class Country extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM countries WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM countries WHERE `Id` = '%d'";
	public $table_name = "countries";
	
	public $prototype = array(
		'string' => array(
			'Name'				=> "name",
			'PrintableName'		=> "printable_name",
			'Iso'				=> "iso",
			'Iso3'				=> "iso3"
		),
		'numeric' => array(
			'NumericCode'		=> "numcode"
		)
	);
}
?>
