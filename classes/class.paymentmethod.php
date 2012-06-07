<?php
if($_VPS !== true) { die("x"); }

class PaymentMethod extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM paymentmethods WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM paymentmethods WHERE `Id` = '%d'";
	public $table_name = "paymentmethods";
	
	public $prototype = array(
		'string' => array(
			'Name'			=> "Name",
			'Icon'			=> "Icon"
		),
		'boolean' => array(
			'IsAnonymous'	=> "Anonymous"
		)
	);
}
?>
