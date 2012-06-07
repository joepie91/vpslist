<?php
$_VPS = true;
$_CPHP = true;
require("cphp/base.php");
require("classes/class.country.php");
require("classes/class.provider.php");
require("classes/class.plan.php");
require("classes/class.plan.priceoption.php");
require("classes/class.plan.locationoption.php");
require("classes/class.paymentmethod.php");
require("classes/class.virtualizationplatform.php");

session_start();

$user = "";
$pw = "";

if(!isset($_SESSION['logged_in']))
{
	if(!isset($_POST['submit']))
	{
		?>
		<form method="post" action="mod.php">
			Username: <input type="text" name="username"><br>
			Password: <input type="password" name="password"><br>
			<button type="submit" name="submit" value="submit">Login</button>
		</form>
		<?php
	}
	else
	{
		if($_POST['username'] == $user && $_POST['password'] == $pw)
		{
			$_SESSION['logged_in'] = true;
			header("Location: mod.php");
			die();
		}
		else
		{
			die("Wrong login details.");
		}
	}
}
else
{
	if(!isset($_GET['action']) || $_GET['action'] == "home")
	{
		$result = mysql_query_cached("SELECT * FROM plans WHERE `Visible` = '0'", 10);
		
		foreach($result->data as $row)
		{
			$plan = new Plan($row);
			
			if($result = mysql_query_cached("SELECT * FROM plan_priceoptions WHERE `PlanId` = '{$plan->sId}' ORDER BY `BillingPeriod` ASC LIMIT 1"))
			{
				$option = new PlanPriceOption($result->data);
				$sPrice = ($option->sPrice / $option->sBillingPeriod) / 100;
				$sPriceMonths = $option->sBillingPeriod;
			}
			else
			{
				$sPrice = 0;
			}
			
			$sPrice = number_format($sPrice, 2, ".", "");
			
			echo("<a href=\"mod.php?action=accept&plan={$plan->sId}\">[Yes]</a>&nbsp;
			<a href=\"mod.php?action=reject&plan={$plan->sId}\">[No]</a>&nbsp;
			{$plan->sProvider->sName} : {$plan->sName} : {$sPrice} : <strong>{$plan->sGuaranteedRam}MB</strong> Guaranteed, <strong>{$plan->sBurstRam}MB</strong> Burst, <strong>{$plan->sDiskSpace}GB</strong> Disk, <strong>{$plan->sCpuCores}</strong> CPU Cores, <strong>{$plan->sTraffic}GB</strong> Traffic, <strong>{$plan->sBandwidth}mbps</strong> Bandwidth
			<br>");
		}
	}
	elseif($_GET['action'] == "accept")
	{
		$plan = new Plan($_GET['plan']);
		$plan->uVisible = true;
		$plan->InsertIntoDatabase();
	}
}

?>
