<?php

class TzController {

	public $tiitzData;  
    public $tzRender;
    public $conf;
    public $route;
    public $tzPlugin;
    public $tzValidator;
    public $Auth;
    private static $tiitzVersion = '0.3';
    
    public function __construct() {
        $this->conf         = tzKernel::$tzConf;
        $this->route        = tzKernel::$tzRoute;
        $this->tzRender     = tzKernel::$tzRender;
    }

    // Made by Tiitz team for Mister Gael Coat, special dedicasse !
    protected function callController($controller, $action) {
        $Controller .= "Controller";
        $Action     .= "Action";

        if (is_file(ROOT.tzKernel::$tzRoute['dirPath'].$controller.'.php')) {
            require_once ROOT.tzKernel::$tzRoute['dirPath'].$controller.'.php';

            if (class_exists($controller)) {
                $newController = new $controller($this->tiitzData);

                if (method_exists($newController, $action))
                    $newController->$action();
                else
                    DebugTool::$error->catchError(array("No action $action Found", __FILE__,__LINE__));
            }
            else
                DebugTool::$error->catchError(array("No Class $controller Found", __FILE__,__LINE__));
        }
        else
            DebugTool::$error->catchError(array('No Controller Found', __FILE__,__LINE__));
    }

}