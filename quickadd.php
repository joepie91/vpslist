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

?>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
<style>
	body
	{
		font-family: "Open Sans", sans-serif;
	}
	
	.success
	{
		background-color: #E8FFC8;
		border: 1px solid #AAFF38;
		padding: 6px;
		font-weight: bold;
	}
	
	.formfield
	{
		margin: 18px 8px;
		background-color: #F6FFE9;
		padding: 9px;
		border: 1px solid #68B800;
	}
	
	h2
	{
		margin-top: 4px;
	}
	
	button
	{
		display: block;
		font-size: 21px;
		margin-left: 200px;
		font-weight: bold;
		font-family: "Open Sans", sans-serif;
	}
</style>
<?php

if(!isset($_GET['action']))
{
	?>
	<h1>VPS plan submission page</h1>
	<p>
		<strong>Note:</strong> submitting providers or plans does not require registration yet, but every submission will be reviewed before becoming
		visible. Typically this happens within 1 or 2 days. Since we have no way of contacting you, check back after 2 days to see if your submissions
		have appeared yet. If not, there was probably an issue with your submission, and you should submit it again with correct details. If you need
		any help on filling in the submission forms, feel free to <a href="http://irc.lc/cryto/crytocc">drop into IRC</a> and ask joepie91 for help.
	</p>
	<a href="?action=provider">Add provider</a><br>
	<a href="?action=plan">Add plan</a><br>
	<!-- <a href="?action=prices">Add pricing</a><br> -->
	<?php
}
elseif($_GET['action'] == "provider")
{
	// form
	
	if(isset($_POST['submit']))
	{
		// process
		$provider = new Provider(0);
		$provider->uName = $_POST['name'];
		$provider->uUrl = $_POST['url'];
		$provider->uCompanyLocation = $_POST['location'];
		$provider->uPlanCount = 0;
		$provider->uUsesMaxmind = 2;
		$provider->uCustomPossible = false;
		$provider->uVisible = false;
		$provider->sSubmissionDate = time();
		$provider->InsertIntoDatabase();
		echo("<div class=\"success\">Your submission has been added and will be reviewed shortly. <a href=\"quickadd.php?action=plan&sub={$provider->sId}\">Click here to add plans for the provider you just added.</a></div>");
	}
	
	?>
	<form method="post" action="?action=provider">
		Provider Name<br>
		<input type="text" name="name"><br>
		<br>
		
		URL<br>
		<input type="text" name="url" value="http://"><br>
		<br>
		
		Company location (NOT server location!)<br>
		<input type="text" name="location"><br>
		<br>
		
		<button type="submit" name="submit" value="submit">Submit</button>
	</form>
	<?php
}
elseif($_GET['action'] == "plan")
{
	if(!isset($_GET['sub']))
	{
		// list providers
		$result = mysql_query_cached("SELECT * FROM providers ORDER BY `Name` ASC");
		
		foreach($result->data as $row)
		{
			$provider = new Provider($row);
			echo("<a href=\"?action=plan&sub={$provider->sId}\">{$provider->sName}</a><br>");
		}
	}
	else
	{
		// form
		$provider_id = $_GET['sub'];
		$provider = new Provider($provider_id);
		
		if(isset($_POST['submit']))
		{
			// process
			$price_defined = false;
			
			foreach($_POST['months'] as $month)
			{
				if(!empty($month))
				{
					$price_defined = true;
				}
			}
			
			if($price_defined === false)
			{
				die("You did not specify any billing periods / prices. Go back and try again.");
			}
			
			$plan = new Plan(0);
			$plan->uProviderId = $provider_id;
			$plan->uName = $_POST['name'];
			$plan->uGuaranteedRam = $_POST['guaranteed'];
			$plan->uBurstRam = $_POST['burst'];
			$plan->uDiskSpace = $_POST['disk'];
			$plan->uTraffic = $_POST['traffic'];
			$plan->uBandwidth = $_POST['bandwidth'];
			$plan->uBackupSpace = $_POST['backup'];
			$plan->uCpuCores = $_POST['cores'];
			$plan->uAllowsIrc = (isset($_POST['irc'])) ? 1 : 0;
			$plan->uIsUnmetered = isset($_POST['unmetered']);
			$plan->uHasIpv4 = isset($_POST['ipv4']);
			$plan->uHasIpv6 = isset($_POST['ipv6']);
			$plan->uFreeDns = isset($_POST['dns']);
			$plan->uOverageBilling = isset($_POST['overage']);
			$plan->uDedicatedCpu = isset($_POST['dedicated_cores']);
			$plan->uVirtualizationPlatformId = $_POST['platform'];
			$plan->uVisible = false;
			$plan->sLastUpdate = time();
			$plan->sSubmissionDate = time();
			$plan->InsertIntoDatabase();
			
			for($i = 0; $i < count($_POST['months']); $i++)
			{
				if(!empty($_POST['months'][$i]))
				{
					$price = new PlanPriceOption(0);
					$price->uBillingPeriod = $_POST['months'][$i];
					$price->uPrice = $_POST['price'][$i] * 100;
					$price->uPlanId = $plan->sId;
					$price->InsertIntoDatabase();
				}
			}
			
			echo("<div class=\"success\">Your submission has been added and will be reviewed shortly.</div>");
		}
		
		$platform_list = "<option vale=\"0\">Unknown</option>";
		$result = mysql_query_cached("SELECT * FROM virtualizationplatforms");
		
		foreach($result->data as $row)
		{
			$platform = new VirtualizationPlatform($row);
			$platform_list .= "<option value=\"{$platform->sId}\">{$platform->sName}</option>";
		}
		
		echo("<h1>Add new plan for {$provider->sName}</h1>");
		?>
		<h3>NOTE: Please only add plans in different locations as separate plans, if the specifications are different. If only the location differs, list
		them as 1 plan! Location data is coming soon.</h3>
		<h3>NOTE: No discount plans/codes please, only plans that are listed on the website permanently.</h3>
		<form method="post" action="?action=plan&sub=<?php echo($provider_id); ?>">
			<div class="formfield">
				<strong>Plan Name</strong><br>
				<sup>Please use the same name as on your website</sup><br>
				<input type="text" name="name">
			</div>
			
			<div class="formfield">
				<strong>Virtualization platform</strong><br>
				<sup>Your platform not listed? <a href="http://irc.lc/cryto/crytocc">Contact joepie91 on IRC.</a></sup><br>
				<select name="platform">
					<?php echo($platform_list); ?>
				</select>
			</div>
			
			<div class="formfield">
				<strong>Guaranteed/dedicated RAM</strong><br>
				<input type="text" name="guaranteed">MB
			</div>
			
			<div class="formfield">
				<strong>Burst RAM</strong><br>
				<sup><strong>Using OpenVZ Burst RAM?</strong> Enter your amount of burst RAM including the guaranteed RAM.</sup><br>
				<sup><strong>Using swap or vSwap?</strong> Enter the TOTAL amount of guaranteed RAM + the amount of swap/vSwap. A better method to indicate
				this will be added in the very near future.</sup><br>
				<sup><strong>Not using burst, swap, or vSwap?</strong> Leave this field empty.</sup><br>
				<input type="text" name="burst">MB
			</div>
			
			<div class="formfield">
				<strong>Disk space</strong><br>
				<input type="text" name="disk">GB
			</div>
			
			<div class="formfield">
				<strong>Traffic</strong><br>
				<sup><strong>Unmetered?</strong> Leave the input field empty and tick the checkbox.</sup><br>
				<sup><strong>Unmetered for either only inbound or outbound?</strong> Enter the traffic for the metered part.</sup><br>
				<input type="text" name="traffic">GB<br>
				<input type="checkbox" name="unmetered" id="unmetered"> <label for="unmetered">Unmetered</label>
			</div>
			
			<div class="formfield">
				<strong>Port speed / bandwidth (NOT traffic!)</strong><br>
				<input type="text" name="bandwidth">mbps
			</div>
			
			<div class="formfield">
				<strong>CPU</strong><br>
				<sup><strong>No defined amount of cores, or fair-share based on other specs?</strong> Leave this field empty.</sup><br>
				<input type="text" name="cores"><br>
				<input type="checkbox" name="dedicated_cores" id="dedicated_cores"> <label for="dedicated_cores">Dedicated</label>
			</div>
			
			<div class="formfield">
				<strong>Free backup space</strong><br>
				<sup><strong>Backup space per customer, rather than per plan?</strong> Just enter the backup space per customer, and ensure your website
				clearly states that backup space is per customer.</sup><br>
				<sup><strong>Backup space only on ticket request?</strong> Just fill in this field. It's recommended to clearly indicate this on your website.</sup><br>
				<input type="text" name="backup" value="0">
			</div>
			
			<div class="formfield">
				<input type="checkbox" name="irc" id="irc" checked> <label for="irc">Allows IRC servers<br><sup>(this is only about daemons, not about clients or bouncers)</sup></label>
			</div>
			<div class="formfield">
				<input type="checkbox" name="dns" id="dns"> <label for="dns">Free DNS hosting</label>
			</div>
			<div class="formfield">
				<input type="checkbox" name="ipv4" id="ipv4" checked> <label for="ipv4">IPv4 connectivity</label><br>
				<input type="checkbox" name="ipv6" id="ipv6"> <label for="ipv6">IPv6 connectivity</label>
			</div>
			<div class="formfield">
				<input type="checkbox" name="overage" id="overage"> <label for="overage">Overage billing<br><sup>(automatic billing for going over traffic quota, as opposed to temporary VPS suspension)</sup></label>
			</div>
			
			<div class="formfield">
				<h2>Billing periods</h2>
				<h3>For multiple-month billing periods, be sure to enter the TOTAL price for all those months together, and NOT the (discounted) price per month.</h3>
				$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
				$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
				$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
				$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
				$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			</div>
			
			<button type="submit" name="submit" value="submit">Submit</button>
		</form>
		<?php
	}
}
/*elseif($_GET['action'] == "prices")
{
	if(!isset($_GET['plan']))
	{
		$result = mysql_query_cached("SELECT * FROM plans ORDER BY `ProviderId`");
		
		foreach($result->data as $row)
		{
			$plan = new Plan($row);
			echo("<a href=\"?action=prices&plan={$plan->sId}\">[{$plan->sProvider->sName}] {$plan->sName}</a><br>");
		}
	}
	else
	{
		$plan_id = $_GET['plan'];
		$plan = new Plan($plan_id);
		
		if(isset($_POST['submit']))
		{
			for($i = 0; $i < count($_POST['months']); $i++)
			{
				if(!empty($_POST['months'][$i]))
				{
					$price = new PlanPriceOption(0);
					$price->uBillingPeriod = $_POST['months'][$i];
					$price->uPrice = $_POST['price'][$i] * 100;
					$price->uPlanId = $plan_id;
					$price->InsertIntoDatabase();
				}
			}
			
			echo("<strong>Done!</strong><br>");
		}
		?>
		<form method="post" action="?action=prices&plan=<?php echo($plan_id); ?>">
			<h2>Billing options for <?php echo("{$plan->sName} ({$plan->sProvider->sName})"); ?></h2>
			$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			$<input type="text" name="price[]"> per <input type="text" name="months[]"> months<br>
			
			<button type="submit" name="submit" value="submit">Submit</button>
		</form>
		<?php
	}
}*/
?>
