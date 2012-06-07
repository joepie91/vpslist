<?php
if($_CPHP !== true) { die(); }

$cphp_mysql_connected = false;

if($cphp_mysql_enabled === true)
{
	if(mysql_connect($cphp_mysql_host, $cphp_mysql_user, $cphp_mysql_pass))
	{
		if(mysql_select_db($cphp_mysql_db))
		{
			$cphp_mysql_connected = true;
		}
	}
}
?>
