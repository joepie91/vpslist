<?php
if($_VPS !== true) { die("x"); }

class Provider extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM providers WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM providers WHERE `Id` = '%d'";
	public $table_name = "providers";
	
	public $prototype = array(
		'string' => array(
			'Name'				=> "Name",
			'Url'				=> "Url",
			'CompanyLocation'	=> "Location"
		),
		'numeric' => array(
			'PlanCount'			=> "PlanCount",
			'UsesMaxmind'		=> "Maxmind"
		),
		'boolean' => array(
			'Visible'			=> "Visible",
			'CustomPossible'	=> "CustomPossible"
		),
		'timestamp' => array(
			'SubmissionDate'	=> "SubmissionDate"
		)
	);
}
?>
