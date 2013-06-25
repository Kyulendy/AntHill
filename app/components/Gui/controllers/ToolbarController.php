<?php

use Components\DebugTools\DebugTool;
use Components\FileManager\TzFileManager;
use Components\SQLEntities\TzSQL;

class ToolbarController {

    public function indexAction() {
        define('PATH_TOOLBAR', 'home');
        $active = 'home';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoConfigurationAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(4, 1);
        $config = $php_config['PHP Core'];
        $active = 'config';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoGeneralAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(1, 1);
        $config = $php_config['PHP Configuration'];
        $active = 'general';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoEnvAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(16, 1);
        $config = $php_config['Environment'];
        $active = 'env';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoVariableAction() {
        define('PATH_TOOLBAR', 'phpinfo-configuration');
        $php_config = DebugTool::$toolbar->phpinfo_array(32, 1);
        $config = $php_config['PHP Variables'];
        $active = 'variable';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function phpinfoModuleAction() {
        define('PATH_TOOLBAR', 'phpinfo-module');
        $module = DebugTool::$toolbar->phpinfo_array(8, 1);
        $active = 'module';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function logAction() {
        define('PATH_TOOLBAR', 'log');
        $logs = DebugTool::$errorExtend->getArrayOfError();
        $active = 'logs';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function generateACLAction() {
        define('PATH_TOOLBAR', 'acl');
        
        if(isset($_POST['generation_acl']))
        {
            $statusACL = FALSE;
            
            $query = "SELECT `id`, `name` FROM acl_groups";
            $data = TzSQL::getPDO()->prepare($query);
            $data->execute();
            $aclGroups = $data->fetchAll(\PDO::FETCH_ASSOC);
            require_once(ROOT.'/app/Components/Spyc/Spyc.php');
            $fm = new TzFileManager(ROOT);
            
            $fm->set_currentItem(ROOT."/app/cache/groups.yml");

            $groups = array();
            foreach($aclGroups as $aclGroup)
                $groups[$aclGroup['id']] = $aclGroup['name'];
            $fm->replace_fileContent('#GROUPS');
            if(!empty($groups) && $fm->replace_fileContent(Spyc::YAMLDump($groups)))
                $statusACL = TRUE;
        }
        
    
        $active = 'acl';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    
    }

    public function configAction() {
        define('PATH_TOOLBAR', 'config-prod');
        $active = 'config-prod';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

    public function configDevAction() {
        define('PATH_TOOLBAR', 'config-dev');
        $active = 'config-dev';
        require_once ROOT.'/app/Components/Gui/views/toolbar-layout.php';
    }

}