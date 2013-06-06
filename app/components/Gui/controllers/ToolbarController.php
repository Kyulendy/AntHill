<?php

class ToolbarController {

    public function indexAction() {
        define('PATH_TOOLBAR', 'home');
        $active = 'home';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoConfigurationAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(4, 1);
        $config = $php_config['PHP Core'];
        $active = 'config';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoGeneralAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(1, 1);
        $config = $php_config['PHP Configuration'];
        $active = 'general';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoEnvAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(16, 1);
        $config = $php_config['Environment'];
        $active = 'env';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoVariableAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(32, 1);
        $config = $php_config['PHP Variables'];
        $active = 'variable';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoModuleAction() {
        define('PATH_TOOLBAR', 'phpinfo-module');
        $module = DebugTool::$toolbar->phpinfo_array(8, 1);
        $active = 'module';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }

    public function logAction() {
        define('PATH_TOOLBAR', 'log');
        $logs = DebugTool::$errorExtend->getArrayOfError();
        $active = 'logs';
        require_once ROOT.'/app/components/Gui/views/toolbar-layout.php';
    }
}