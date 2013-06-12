<?php

class TzRouter {

	private static $arrayRoute;

	private static function checkRequirement(array $req, array $params) { // Check if requirements of the route are OK

		$valid = array();	// Array use to test if all requirements return true
		$match = array();	// Array use to test if all regexp return true

		// if a requirement have the name of a URL-variable, we check if the requirement is valid
		foreach ($params as $k => $v) {
			if (array_key_exists($params[$k]["name"], $req)) {
				if ($req[$params[$k]["name"]] == "int") {
					$valid['int'] = (intval($params[$k]["value"])) ? true : false;
				}
				elseif ($req[$params[$k]["name"]] == "string") {
					$valid['string'] = (is_string($params[$k]["value"])) ? true : false;
				}
				else {
					if (preg_match("/".$req[$params[$k]["name"]]."/", $params[$k]["value"])) {
						$match[$params[$k]["name"]] = true;
					}
					else {
						$match[$params[$k]["name"]] = false;
					}
				}
			}	
		}

		// Test if data were send by post or get method
		if (array_key_exists("_method", $req)) {
			if ($req["_method"] == "post" || $req["_method"] == "POST")
				$valid['post'] = (!empty($_POST)) ? true : false;

			if ($req["_method"] == "get" || $req["_method"] == "GET")
				$valid['get'] = (!empty($_GET)) ? true : false;
		}

		// Test if the page was called by Ajax request
		if (array_key_exists("ajax", $req)) {
			if ($req["ajax"] == true) {
				if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
					$valid['ajax'] = true;
				else
					$valid['ajax'] = false;
			}
		}

		// Checking if regexp matchs
		foreach ($match as $value) {
			if ($value === false)
				$valid["regexp"] = false;
		}

		// Check if requirements are valid
		foreach ($valid as $value) {
			if ($value === false)
				return false;
		}
		return true;
	}

	private static function deleteEmptyValues(array $array) {
		$arrayReturn = array();
		if(count($array) == 2 && ($array[0] == '' && $array[1] == ''))
			$arrayReturn = array();
		foreach ($array as $key => $value) {
			if($value !== '')
				$arrayReturn[] = $value;
		}
		return $arrayReturn;
	}

	private static function parseRoutes(array $arrayRoutes, array $actualRoute, $mode, array $conf) { // Fonction comparant les routes
		if($mode == "gui")
			$type='config';
		elseif(!empty($conf["routingType"]) && $conf["routingType"] == "php")
			$type = 'site_php';
		else
			$type = 'site_yaml';

		#var_dump(ROOT.$arrayRoutes[$type]['ressource']);
		
		if(!file_exists(ROOT.$arrayRoutes[$type]['ressource'])) {
			DebugTool::$error->catchError(array('Routing file missing', __FILE__,__LINE__, true));
		}
		if($type == "config" || $type == "site_yaml")
			$arraySubRoutes = Spyc::YAMLLoad(ROOT.$arrayRoutes[$type]['ressource']);
		elseif($type == "site_php") {
			require_once ROOT.$arrayRoutes[$type]['ressource'];
			$arraySubRoutes = $tzRoute;
		}

		#echo "ROUTE : ";var_dump($arraySubRoutes);echo "--------------<br />";

		foreach ($arraySubRoutes as $key => $params) {
			$arraySubRoutes[$key]['pattern'] = self::deleteEmptyValues(explode('/', $arraySubRoutes[$key]['pattern']));
			$arraySubRoutes[$key]['type'] = $type;
			$arraySubRoutes[$key]['params'] = array();

			foreach ($arraySubRoutes[$key]['pattern'] as $_key => $value) {
				if(strpos($value, '{') === 0 && strpos($value , '}') === strlen($value) -1 ) {
					$value = str_replace('{', '', $value);
					$value = str_replace('}', '', $value);
					if(!empty($actualRoute[$_key])) {
						$arraySubRoutes[$key]['params'][] = array('name' => $value, 'position' => $_key , 'value' => $actualRoute[$_key]);
						$arraySubRoutes[$key]['pattern'][$_key] = $actualRoute[$_key];
					}
				}

			}

			$r = true;
			if (!empty($arraySubRoutes[$key]['requirements']))
				$r = self::checkRequirement($arraySubRoutes[$key]['requirements'], $arraySubRoutes[$key]['params']);

			if($arraySubRoutes[$key]['pattern'] == $actualRoute && $r)
				return $arraySubRoutes[$key];
		}
		return false;
	}

	public static function getRoute(array $conf, $mode = "defaults") { // Fonction retournant la route correspondant a PATH_INFO
		if(empty($_SERVER['PATH_INFO']) || $_SERVER['PATH_INFO'] == '/')
			$urlParams = array();
		else
			$urlParams = self::deleteEmptyValues(explode('/', $_SERVER['PATH_INFO']));

		$yaml = Spyc::YAMLLoad(ROOT.'/app/config/routing.yml');
		
		if(!empty($urlParams[0]) && $urlParams['0'] === 'configTiitz') {
			$mode = 'gui';
		}

		$selectedRoute = self::parseRoutes($yaml, $urlParams, $mode, $conf);

		#echo 'URL PARAM :';var_dump($urlParams);echo '---------<br />';
		#echo 'URL PARAM :';var_dump($urlParams);echo '---------<br />';
		#echo 'YAML SRC :';var_dump($yaml);echo '---------<br />';
        #echo "SELECTED ROUTE : ";var_dump($selectedRoute);echo "--------------<br />";

		if($selectedRoute){
			$arrayController = explode(':', $selectedRoute['controller']);
			if(count($arrayController) !== 2){
				self::$arrayRoute = 'Error While parsing Controller route';
			} else {
				if($selectedRoute['type'] == 'config') {
					self::$arrayRoute['dirPath'] = '/app/components/Gui/controllers/';
					self::$arrayRoute['path'] = self::$arrayRoute['dirPath'].$arrayController[0].'Controller.php';
				} else {
					self::$arrayRoute['dirPath'] = '/src/controllers/';
					self::$arrayRoute['path'] = self::$arrayRoute['dirPath'].$arrayController[0].'Controller.php';
				}
				self::$arrayRoute['action'] = $arrayController[1].'Action';
                                self::$arrayRoute['className'] = $arrayController[0].'Controller';
				self::$arrayRoute['params'] = $selectedRoute['params'];
				if(!empty($selectedRoute['requirements'])){
					if(!empty($selectedRoute['requirements']['allow_groups'])){
						self::$arrayRoute['requirements']['allow_groups'] = $selectedRoute['requirements']['allow_groups'];
					}
					if(!empty($selectedRoute['requirements']['exclude_groups'])){
						self::$arrayRoute['requirements']['exclude_groups'] = $selectedRoute['requirements']['exclude_groups'];
					}
					if(!empty($selectedRoute['requirements']['only_connected'])){
						self::$arrayRoute['requirements']['only_connected'] = $selectedRoute['requirements']['only_connected'];
					}

				}
			}
		}
		return self::$arrayRoute;
	}

	public static function getNotFoundRoute() {

		self::$arrayRoute['dirPath'] 	= "/src/controllers/";
		self::$arrayRoute['path'] 		= "/src/controllers/pageNotFoundController.php";
		self::$arrayRoute['action'] 	= "showAction";
		self::$arrayRoute['className'] = "pageNotFoundController";
		return self::$arrayRoute;
	}
}
