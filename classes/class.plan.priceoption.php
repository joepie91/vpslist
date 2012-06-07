<?php
if($_VPS !== true) { die("x"); }

class PlanPriceOption extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM plan_priceoptions WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM plan_priceoptions WHERE `Id` = '%d'";
	public $table_name = "plan_priceoptions";
	
	public $prototype = array(
		'numeric' => array(
			'BillingPeriod'		=> "BillingPeriod",
			'Price'				=> "Price",
			'PlanId'			=> "PlanId"
		),
		'plan' => array(
			'Plan'				=> "PlanId"
		)
	);
}
?>
