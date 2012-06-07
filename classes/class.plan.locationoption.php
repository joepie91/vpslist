<?php
if($_VPS !== true) { die("x"); }

class PlanLocationOption extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM plan_locationoptions WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM plan_locationoptions WHERE `Id` = '%d'";
	public $table_name = "plan_locationoptions";
	
	public $prototype = array(
		'numeric' => array(
			'PlanId'			=> "PlanId",
			'LocationId'		=> "LocationId"
		),
		'plan' => array(
			'Plan'				=> "PlanId"
		),
		'location' => array(
			'Location'			=> "LocationId"
		)
	);
}
?>
