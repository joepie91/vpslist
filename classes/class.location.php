<?php
if($_VPS !== true) { die("x"); }

class Location extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM locations WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM locations WHERE `Id` = '%d'";
	public $table_name = "locations";
	
	public $prototype = array(
		'string' => array(
			'Location'			=> "Location",
			'TestIp4'			=> "TestIp4",
			'TestIp6'			=> "TestIp6",
			'TestFile'			=> "TestFile"
		),
		'numeric' => array(
			'CountryId'			=> "CountryId",
			'ProviderId'		=> "ProviderId"
		),
		'country' => array(
			'Country'			=> "CountryId"
		),
		'provider' => array(
			'Provider'			=> "ProviderId"
		)
	);
}
?>
