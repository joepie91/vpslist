<?php
if($_VPS !== true) { die("x"); }

class Plan extends CPHPDatabaseRecordClass
{
	public $fill_query = "SELECT * FROM plans WHERE `Id` = '%d'";
	public $verify_query = "SELECT * FROM plans WHERE `Id` = '%d'";
	public $table_name = "plans";
	
	public $prototype = array(
		'string' => array(
			'Name'						=> "Name"
		),
		'numeric' => array(
			'ProviderId'				=> "ProviderId",
			'Traffic'					=> "Traffic",
			'DiskSpace'					=> "DiskSpace",
			'CpuCores'					=> "CpuCores",
			'Bandwidth'					=> "Bandwidth",
			'GuaranteedRam'				=> "GuaranteedRam",
			'BurstRam'					=> "BurstRam",
			'AllowsIrc'					=> "AllowsIrc",
			'BackupSpace'				=> "BackupSpace",
			'VirtualizationPlatformId'	=> "VirtualizationPlatformId"
		),
		'boolean' => array(
			'IsUnmetered'				=> "Unmetered",
			'HasIpv4'					=> "HasIpv4",
			'HasIpv6'					=> "HasIpv6",
			'FreeDns'					=> "FreeDns",
			'OverageBilling'			=> "OverageBilling",
			'DedicatedCpu'				=> "DedicatedCpu",
			'Visible'					=> "Visible"
		),
		'timestamp' => array(
			'LastUpdate'				=> "LastUpdated",
			'SubmissionDate'			=> "SubmissionDate"
		),
		'provider' => array(
			'Provider'					=> "ProviderId"
		),
		'virtualizationplatform' => array(
			'VirtualizationPlatform'	=> "VirtualizationPlatformId"
		)
	);
}
?>
