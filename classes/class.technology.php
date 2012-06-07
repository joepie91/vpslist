<?php
if($_VPS !== true) { die("x"); }

class VirtualizationTechnology extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM technologies WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM technologies WHERE `Id` = '%d'";
	public $table_name = "technologies";
	
	public $prototype = array(
		'string' => array(
			'Name'				=> "Name"
		)
	);
}
?>
