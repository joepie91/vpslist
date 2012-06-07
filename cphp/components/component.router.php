<?php
class CPHPRouter extends CPHPBaseClass
{
	public $routes = array();
	public $parameters = array();
	
	public function RouteRequest()
	{
		eval(extract_globals()); // hack hackity hack hack
		
		if(isset($_SERVER['REQUEST_URI']))
		{
			$requestpath = trim($_SERVER['REQUEST_URI']);
		}
		else
		{
			$requestpath = "/";
		}
		
		$found = false;  // Workaround because a break after an include apparently doesn't work in PHP.
		
		foreach($this->routes as $priority)
		{
			foreach($priority as $route_regex => $route_destination)
			{
				if($found === false)
				{
					$regex = str_replace("/", "\/", $route_regex);
					if(preg_match("/{$regex}/i", $requestpath, $matches))
					{
						$this->uParameters = $matches;
						include($route_destination);
						$found = true;
					}
				}
			}
		}
	}
}
?>
