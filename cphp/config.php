<?php
if($_CPHP !== true) { die(); }

$cphp_class_map = array(
	'country'			=> "Country",
	'provider'			=> "Provider",
	'plan'				=> "Plan",
	'paymentmethod'			=> "PaymentMethod",
	'virtualizationplatform'	=> "VirtualizationPlatform"
);

$cphp_locale_name = "english";
$cphp_locale_path = "locales";
$cphp_locale_ext  = "lng";

$cphp_usersettings[CPHP_SETTING_TIMEZONE] = "Europe/Amsterdam";

/* These are the memcache settings. You will need to have memcache set
 * up on your server to use these. Compression requires zlib. */
$cphp_memcache_enabled 		= true;			// Whether to user memcache.
$cphp_memcache_server		= "localhost";	// The hostname of the memcached
$cphp_memcache_port			= 11211;		// The port number of memcached
$cphp_memcache_compressed	= true;			// Whether to compress memcache objects

$cphp_mysql_enabled = true;
$cphp_mysql_host = "localhost";
$cphp_mysql_user = "vps";
$cphp_mysql_pass = "";
$cphp_mysql_db 	= "vps";

$cphp_components = array(
	"router",
	"formbuilder",
	"errorhandler"
);
?>
