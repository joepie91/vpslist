<?php
if($_VPS !== true) { die("x"); }

class VirtualizationPlatform extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM virtualizationplatforms WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM virtualizationplatforms WHERE `Id` = '%d'";
	public $table_name = "virtualizationplatforms";
	
	public $prototype = array(
		'string' => array(
			'Name'				=> "Name"
		)
	);
}
?>
