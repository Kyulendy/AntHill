<?php
	session_start();
	// Global configuration for the framework
	require_once("../app/components/Tiitz/Tiitz.php");
	$tiitz = new TiiTz();
	define("ROOT", realpath(__DIR__."/../")); // base of the web site

	// Array containing Main data use in Tiitz Kernel and Tiitz Controllers
	$tiitzData = array();

	// Include YAML parsing tool
	require_once(ROOT.'/app/components/Spyc/Spyc.php');
	$comp = Spyc::YAMLLoad(ROOT.'/app/config/components.yml');
	$conf = Spyc::YAMLLoad(ROOT.'/app/config/config.yml');
    $conf_dev = Spyc::YAMLLoad(ROOT.'/app/config/config_dev.yml');
	// Include the components contains in components.yml
	foreach ($comp as $k => $v) {
		require_once(ROOT.$v);
	}
	// Error manager
	DebugTool::initDebugTools('0.3', $conf_dev);
	echo $lol;
	if (!empty($conf["template"]))
		$tzRender = TzRender::getInstance($conf["template"]);
	else
		$tzRender = TzRender::getInstance("");

	if (!empty($conf["existingproject"]) && $conf["existingproject"] === true) 
		$route = TzRouter::getRoute($conf);
	else
		$route = TzRouter::getRoute($conf, "gui");

	// We fill $tiitzData with tiitz infos
	$tiitzData['route'] 	= $route;
	$tiitzData['conf'] 		= $conf;
	$tiitzData['tzRender'] 	= $tzRender;

	if(!empty($conf['database']['user']) && !empty($conf["existingproject"]) && $conf["existingproject"] === true) {
		TzSQL::getInstance($conf['database']['host'],$conf['database']['user'],$conf['database']['password'],$conf['database']['dbname']);
	}

	if (is_file(ROOT.$route["path"])) {
		require_once ROOT.$route["path"];

		if(!empty($route['params'])){
			foreach ($route['params'] as $value) {
				$tiitzData['params'][$value['name']] = $value['value'];
			}
		}
	}
	else {
		// Define 404 route
		$route['dirPath'] 	= "/src/controllers/";
		$route['path'] 		= "/src/controllers/pageNotFoundController.php";
		$route['action'] 	= "showAction";
		$route['className'] = "pageNotFoundController";

		if (is_file(ROOT.$route["path"])) {
			require_once ROOT.$route["path"];
		}
	}

	// We create the controller instance and call the requested actions
	if (class_exists($route["className"])) {
        $controller = new $route["className"]($tiitzData);

        if (method_exists($controller, $route["action"])) {
        	if(!empty($route['params'])){
        		$controller->$route["action"]($tiitzData['params']);
        	} else {
        		$controller->$route["action"]();
        	}
        }
            
        else
            DebugTool::$error->catchError(array("No action ".$route["action"]." Found", __FILE__,__LINE__));
    }
    else
        DebugTool::$error->catchError(array("No Class ".$route["className"]." Found", __FILE__,__LINE__));
