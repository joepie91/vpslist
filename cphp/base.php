<?php
require("include.constants.php");

require("config.php");

require("include.dependencies.php");
require("include.exceptions.php");
require("include.datetime.php");
require("include.misc.php");

require("include.memcache.php");
require("include.mysql.php");
require("include.session.php");

require("class.templater.php");
require("class.localizer.php");

$locale = new Localizer();
$locale->Load($cphp_locale_name);

setlocale(LC_ALL, $locale->locale);

require("class.base.php");
require("class.databaserecord.php");

foreach($cphp_components as $component)
{
	require("components/component.{$component}.php");
}
?>
