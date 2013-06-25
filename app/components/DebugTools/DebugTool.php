<?php

namespace Components\DebugTools;
use Components\DebugTools\Toolbar\Toolbar;
use Components\DebugTools\ErrorManager\ErrorManager;
use Components\DebugTools\ErrorManager\ErrorManagerExtend;

class DebugTool {

	private static $instance;
	public static  $toolbar;
	public static  $error;
	public static  $errorExtend;

	private function __construct ($frameworkVersion, $params) {
		self::$toolbar 		= new Toolbar($frameworkVersion);
		self::$error 		= new ErrorManager($params['error']);
		self::$errorExtend 	= new ErrorManagerExtend($params);
	}

	public static function initDebugTools ($frameworkVersion, $params) {
		self::$instance = new DebugTool($frameworkVersion, $params);
	}
	public function getInstanceToolbar () {
		return $this->instance;
	}
	public function getInstanceError () {
		return $this->instanceError;
	}
}