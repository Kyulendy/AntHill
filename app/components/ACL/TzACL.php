<?php

namespace Components\ACL;

use Components\DebugTools\DebugTool;
use Components\Auth\TzAuth;
use Components\Spyc\Spyc;

class TzACL {
    private static $groupList;
    private static $userGroup;
    private static $authorization = array();

    public static function checkPermissions(array $requirements){
        if((!empty($requirements['exclude_groups']) || !empty($requirements['allow_groups'])) && is_null(self::$groupList)){
            if(file_exists(ROOT.'/app/cache/groups.yml')){
                self::$groupList = Spyc::YAMLLoad(ROOT.'/app/cache/groups.yml');
            } else {
                DebugTool::$error->catchError(array("No groups.yml file found", __FILE__,__LINE__),true);
            }
        }

        if(isset($requirements['only_connected']) && $requirements['only_connected'] == true && !TzAuth::isUserLoggedIn()){
            self::$authorization['connect'] = false;
        }
        if(TzAuth::isUserLoggedIn() && !empty(self::$groupList)){
            $idGroup = TzAuth::readUser('acl_group_id');
            if(isset(self::$groupList[$idGroup])){
                self::$userGroup = self::$groupList[$idGroup];
            } else {
                DebugTool::$error->catchError(array("Missing group. Please update groups.yml", __FILE__,__LINE__),true);
            }
        }

        if(!empty($requirements['allow_groups'])){
          $allowedGroups = explode('|', $requirements['allow_groups']);
          if(in_array(self::$userGroup, $allowedGroups)){
            self::$authorization['allowed'] = true;
          } else {
            self::$authorization['allowed'] = false;
          }
        }
        
        if(!empty($requirements['exclude_groups'])){
          $excluedGroups = explode('|', $requirements['exclude_groups']);
          if(in_array(self::$userGroup, $excluedGroups)){
            self::$authorization['exclude'] = false;
          } else {
            self::$authorization['exclude'] = true;
          }
        }
        
        $authorization = true;
        foreach (self::$authorization as $bool) {
            if($bool === false){
                $authorization = false;
            }
        }

        return $authorization;
    }
}