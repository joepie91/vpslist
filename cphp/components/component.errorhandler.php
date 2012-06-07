<?php
cphp_dependency_provides("cphp_errorhandler", "0.1");

define("CPHP_ERRORHANDLER_TYPE_ERROR",			90001	);
define("CPHP_ERRORHANDLER_TYPE_INFO",			90002	);
define("CPHP_ERRORHANDLER_TYPE_WARNING",		90003	);
define("CPHP_ERRORHANDLER_TYPE_SUCCESS",		90004	);

class CPHPErrorHandler
{
	public $sErrorType = CPHP_ERRORHANDLER_TYPE_ERROR;
	public $sLogError = true;
	public $sTitle = "";
	public $sMessage = "";
	
	public function __construct($type, $title, $message, $log = true)
	{
		$this->sErrorType = $type;
		$this->sLogError = $log;
		$this->sTitle = $title;
		$this->sMessage = $message;
	}
	
	public function LogError($context, $message)
	{
		// FIXME placeholder function, error logging has not been implemented yet
	}
	
	public function Render()
	{
		global $locale;
		
		$template['error'] = new Templater();
		
		switch($this->sErrorType)
		{
			case CPHP_ERRORHANDLER_TYPE_ERROR:
				$template['error']->Load("errorhandler.error");
				break;
			case CPHP_ERRORHANDLER_TYPE_INFO:
				$template['error']->Load("errorhandler.info");
				break;
			case CPHP_ERRORHANDLER_TYPE_WARNING:
				$template['error']->Load("errorhandler.warning");
				break;
			case CPHP_ERRORHANDLER_TYPE_SUCCESS:
				$template['error']->Load("errorhandler.success");
				break;
		}
		
		$template['error']->Localize($locale->strings);
		$template['error']->Compile(array(
			'title'		=> $this->sTitle,
			'message'	=> $this->sMessage
		));
		
		return $template['error']->Render();
	}
}
?>
