<?php
if($_CPHP !== true) { die(); }

class Localizer
{
	public $strings = array();
	public $locale = "";
	public $datetime_short = "";
	public $datetime_long = "";
	public $date_short = "";
	public $date_long = "";
	public $time = "";
	
	public function Load($locale)
	{
		global $cphp_locale_path, $cphp_locale_ext;
		
		$this->strings = array();
		$lng_contents = file_get_contents("{$cphp_locale_path}/{$locale}.{$cphp_locale_ext}");
		if($lng_contents !== false)
		{
			$lines = explode("\n", $lng_contents);
			foreach($lines as $line)
			{
				$line = str_replace("\r", "", $line);
				if(preg_match("/(.+?[^\\\]);(.+)/", $line, $matches))
				{
					$key = trim(str_replace("\;", ";", $matches[1]));
					$value = trim(str_replace("\;", ";", $matches[2]));
					switch($key)
					{
						case "_locale":
							$this->locale = explode(",", $value);
							break;
						case "_datetime_short":
							$this->datetime_short = $value;
							break;
						case "_datetime_long":
							$this->datetime_long = $value;
							break;
						case "_date_short":
							$this->date_short = $value;
							break;
						case "_date_long":
							$this->date_long = $value;
							break;
						case "_time":
							$this->time = $value;
							break;
						default:
							$this->strings[$key] = $value;
							break;
					}
				}
			}
		}
		else
		{
			Throw new Exception("Failed to load locale {$locale}.");
		}
	}
}

?>
