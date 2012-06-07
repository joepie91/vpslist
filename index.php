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

$template['frame'] = new Templater();
$template['frame']->Load("frame");
$template['frame']->Localize($locale->strings);

$sPageContents = "";
$sPageTitle = "";
$sPageHeadScript = "";

if(empty($_GET['action']) || $_GET['action'] == "home")
{
	// home page
	header("Location: index.php?action=list");
	die();
}
elseif($_GET['action'] == "list")
{
	// list/filter
	$template['main'] = new Templater();
	$template['main']->Load("page.list");
	$template['main']->Localize($locale->strings);
	
	$template['vps'] = new Templater();
	$template['vps']->Load("element.vps");
	
	$sList = "";
	
	$min_ram = 0;
	$max_ram = 0;
	$min_burst = 0;
	$max_burst = 0;
	$min_disk = 0;
	$max_disk = 0;
	$min_traffic = 0;
	$max_traffic = 0;
	$min_bandwidth = 0;
	$max_bandwidth = 0;
	$min_cpu = 0;
	$max_cpu = 0;
	$min_price = 0;
	$max_price = 0;
	$min_backup = 0;
	$max_backup = 0;
	
	$even = true;
	
	if($result = mysql_query_cached("SELECT * FROM plans WHERE `Visible` = '1' ORDER BY `VirtualizationPlatformId`, `GuaranteedRam`"))
	{
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
			
			if($plan->sGuaranteedRam < $min_ram)
			{
				$min_ram = $plan->sGuaranteedRam;
			}
			elseif($plan->sGuaranteedRam > $max_ram)
			{
				$max_ram = $plan->sGuaranteedRam;
			}
			
			if($plan->sDiskSpace < $min_disk)
			{
				$min_disk = $plan->sDiskSpace;
			}
			elseif($plan->sDiskSpace > $max_disk)
			{
				$max_disk = $plan->sDiskSpace;
			}
			
			if($plan->sBurstRam < $min_burst)
			{
				$min_burst = $plan->sBurstRam;
			}
			elseif($plan->sBurstRam > $max_burst)
			{
				$max_burst = $plan->sBurstRam;
			}
			
			if($plan->sTraffic < $min_traffic)
			{
				$min_traffic = $plan->sTraffic;
			}
			elseif($plan->sTraffic > $max_traffic)
			{
				$max_traffic = $plan->sTraffic;
			}
			
			if($plan->sBandwidth < $min_bandwidth)
			{
				$min_bandwidth = $plan->sBandwidth;
			}
			elseif($plan->sBandwidth > $max_bandwidth)
			{
				$max_bandwidth = $plan->sBandwidth;
			}
			
			if($plan->sCpuCores < $min_cpu)
			{
				$min_cpu = $plan->sCpuCores;
			}
			elseif($plan->sCpuCores > $max_cpu)
			{
				$max_cpu = $plan->sCpuCores;
			}
			
			if($plan->sBackupSpace < $min_backup)
			{
				$min_backup = $plan->sBackupSpace;
			}
			elseif($plan->sBackupSpace > $max_backup)
			{
				$max_backup = $plan->sBackupSpace;
			}
			
			if($sPrice < $min_price)
			{
				$min_price = $sPrice;
			}
			elseif($sPrice > $max_price)
			{
				$max_price = $sPrice;
			}
			
			$sGuaranteedRam = "{$plan->sGuaranteedRam}MB";
			$sDiskSpace = "{$plan->sDiskSpace}GB";
			$sTraffic = ($plan->sIsUnmetered) ? "Unmetered" : "{$plan->sTraffic}GB";
			$sBandwidth = "@ {$plan->sBandwidth}mbit";
			$sPlatform = ($plan->sVirtualizationPlatform->sId == 0) ? "Unknown" : $plan->sVirtualizationPlatform->sName;
			
			$sPrice = number_format($sPrice, 2, ".", "");
			
			$sPriceOriginal = $sPrice;
			
			if($sPriceMonths > 1)
			{
				$sPrice .= " <strong>(x {$sPriceMonths})</strong>";
			}
			
			$sIpv4 = ($plan->sHasIpv4) ? "<img src=\"images/icons/yes.png\" alt=\"Yes\">" : "<img src=\"images/icons/no.png\" alt=\"No\">";
			$sIpv6 = ($plan->sHasIpv6) ? "<img src=\"images/icons/yes.png\" alt=\"Yes\">" : "<img src=\"images/icons/no.png\" alt=\"No\">";
			$sIrc = ($plan->sAllowsIrc) ? "<img src=\"images/icons/yes.png\" alt=\"Yes\">" : "<img src=\"images/icons/no.png\" alt=\"No\">";
			$sFreeDns = ($plan->sFreeDns) ? "<img src=\"images/icons/yes.png\" alt=\"Yes\">" : "<img src=\"images/icons/no.png\" alt=\"No\">";
			$sOverageBilling = ($plan->sOverageBilling) ? "<img src=\"images/icons/yes.png\" alt=\"Yes\">" : "<img src=\"images/icons/no.png\" alt=\"No\">";
			$sBackupSpace = ($plan->sBackupSpace == 0) ? "None" : "{$plan->sBackupSpace}GB";
			$sBurstRam = ($plan->sBurstRam == 0) ? "" : "{$plan->sBurstRam}MB";
			
			if($plan->sCpuCores == 0)
			{
				$sCpuCores = "<img src=\"images/icons/unknown.png\" alt=\"Unknown amount of CPU cores\">";
			}
			else
			{
				$sCpuCores = $plan->sCpuCores;
			}
			
			if($plan->sDedicatedCpu === false)
			{
				$sCpuCores = "{$sCpuCores}&nbsp;<img src=\"images/icons/shared.png\" alt=\"Shared CPU\" title=\"Shared CPU\">";
			}
			else
			{
				$sCpuCores = "{$sCpuCores}&nbsp;<img src=\"images/icons/dedicated.png\" alt=\"Dedicated CPU\" title=\"Dedicated CPU\">";
			}
			
			$template['vps']->Reset();
			$template['vps']->Compile(array(
				'provider-name'					=> $plan->sProvider->sName,
				'plan-name'						=> $plan->sName,
				'platform'						=> $sPlatform,
				'guaranteed-ram'				=> $sGuaranteedRam,
				'burst-ram'						=> $sBurstRam,
				'cpu-cores'						=> $sCpuCores,
				'disk-space'					=> $sDiskSpace,
				'traffic'						=> $sTraffic,
				'bandwidth'						=> $sBandwidth,
				'ipv4'							=> $sIpv4,
				'ipv6'							=> $sIpv6,
				'irc'							=> $sIrc,
				'free-dns'						=> $sFreeDns,
				'overage-billing'				=> $sOverageBilling,
				'backup-space'					=> $sBackupSpace,
				'virtualization-platform'		=> "OpenVZ",
				'price'							=> "\${$sPrice}",
				'data-provider-id'				=> $plan->sProvider->sId,
				'data-cpu-cores'				=> $plan->sCpuCores,
				'data-dedicated-cpu'			=> ($plan->sDedicatedCpu)?1:0,
				'data-guaranteed-ram'			=> $plan->sGuaranteedRam,
				'data-burst-ram'				=> $plan->sBurstRam,
				'data-disk-space'				=> $plan->sDiskSpace,
				'data-traffic'					=> $plan->sTraffic,
				'data-bandwidth'				=> $plan->sBandwidth,
				'data-unmetered'				=> ($plan->sIsUnmetered)?1:0,
				'data-irc'						=> ($plan->sAllowsIrc)?1:0,
				'data-ipv4'						=> ($plan->sHasIpv4)?1:0,
				'data-ipv6'						=> ($plan->sHasIpv6)?1:0,
				'data-free-dns'					=> ($plan->sFreeDns)?1:0,
				'data-overage-billing'			=> ($plan->sOverageBilling)?1:0,
				'data-virtualization-platform'	=> 0,
				'data-price'					=> $sPriceOriginal,
				'data-backup'					=> $plan->sBackupSpace,
				'provider-url'					=> $plan->sProvider->sUrl,
				'data-platform'					=> $plan->sVirtualizationPlatform->sId,
				'color'							=> ($even) ? "row-even" : "row-odd"
			));
			$template['vps']->Localize($locale->strings);
			$sList .= $template['vps']->Render();
			
			$even = !$even;
		}
	}
	
	$template['main']->Compile(array(
		'list'			=> $sList,
		'min-ram'		=> $min_ram,
		'max-ram'		=> $max_ram,
		'min-burst'		=> $min_burst,
		'max-burst'		=> $max_burst,
		'min-disk'		=> $min_disk,
		'max-disk'		=> $max_disk,
		'min-traffic'	=> $min_traffic,
		'max-traffic'	=> $max_traffic,
		'min-bandwidth'	=> $min_bandwidth,
		'max-bandwidth'	=> $max_bandwidth,
		'min-cpu'		=> $min_cpu,
		'max-cpu'		=> $max_cpu,
		'min-price'		=> $min_price,
		'max-price'		=> $max_price,
		'min-backup'	=> $min_backup,
		'max-backup'	=> $max_backup
	));
	
	$sPageHeadScript = "var min_ram = {$min_ram}, max_ram = {$max_ram}, min_burst = {$min_burst}, max_burst = {$max_burst}, min_disk = {$min_disk}, max_disk = {$max_disk},
	min_traffic = {$min_traffic}, max_traffic = {$max_traffic}, min_bandwidth = {$min_bandwidth}, max_bandwidth = {$max_bandwidth}, min_cpu = {$min_cpu}, max_cpu = {$max_cpu}, 
	min_price = {$min_price}, max_price = {$max_price}, min_backup = {$min_backup}, max_backup = {$max_backup};";
	
	$sPageContents = $template['main']->Render();
}
elseif($_GET['action'] == "donate")
{
	$template['main'] = new Templater();
	$template['main']->Load("page.donate");
	$template['main']->Localize($locale->strings);
	$sPageContents = $template['main']->Render();
	$sPageTitle = "Donating";
}
/*elseif($_GET['action'] == "add")
{
	// submission form
	$template['main'] = new Templater();
	$template['main']->Load("page.add");
	$template['main']->Localize($locale->strings);
	
	$form = new CPHPFormBuilder("post", "?action=add");
	
	$section_info = new CPHPFormSection(true, "Plan information");
	$section_info->AddElement(new CPHPFormTextInput("Plan name", "name", "", "The name of the plan as indicated on the providers website"));
	$select_provider = new CPHPFormSelect("Provider", "provider", "", "The provider offering the plan");
	$gr1 = new CPHPFormSelectOptionGroup("Group 1");
	$gr1->AddOption(new CPHPFormSelectOption("val1", "Description 1"));
	$gr1->AddOption(new CPHPFormSelectOption("val2", "Description 2"));
	$select_provider->AddOption($gr1);
	$gr2 = new CPHPFormSelectOptionGroup("Group 2");
	$gr2->AddOption(new CPHPFormSelectOption("val1", "Description 1"));
	$gr2->AddOption(new CPHPFormSelectOption("val2", "Description 2"));
	$select_provider->AddOption($gr2);
	$gr3 = new CPHPFormSelectOptionGroup("Group 3");
	$gr3->AddOption(new CPHPFormSelectOption("val1", "Description 1"));
	$gr3->AddOption(new CPHPFormSelectOption("val2", "Description 2"));
	$select_provider->AddOption($gr3);
	$select_provider->AddOption(new CPHPFormSelectOption("val1", "Description 1"));
	$select_provider->AddOption(new CPHPFormSelectOption("val2", "Description 2"));
	
	$section_info->AddElement($select_provider);
	
	$form->AddElement($section_info);
	
	$template['main']->Compile(array(
		'form'		=> $form->Render()
	));
	
	$sPageContents = $template['main']->Render();
}*/

$template['frame']->Compile(array(
	'title'			=> $sPageTitle,
	'contents'		=> $sPageContents,
	'head-script'	=> $sPageHeadScript
));
$template['frame']->Output();
?>
