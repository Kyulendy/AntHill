<?php
/**
 * Global initialisation for Tiitz framework
 */
class TiiTz {
    
    private $url;

   	public function __construct() {
   		$this->initConfig();
   		$this->initWebPath();
   	}

   	private function initConfig() {
   		// Avoid internal error 500 with E_PARSE Error
		ini_set('display_errors','On');
   	}

   	private function initWebPath() {
   		$pageURL = 'http';

		if ( isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}

		$pageURL .= "://";

		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
		}

		$pageURL = str_replace('index.php', '', $pageURL);
		if(!empty($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] !== '/') {
			$pageURL = str_replace($_SERVER['PATH_INFO'], '', $pageURL);
			$pageURL .= '/';
		}
		while($pageURL[strlen($pageURL)-1] == '/')
		{
			$pageURL = substr_replace($pageURL ,"",-1);
		}
		define('WEB_PATH', $pageURL);
 	}

   	public function getUrl() {
   		return $this->url;
   	}

}  
