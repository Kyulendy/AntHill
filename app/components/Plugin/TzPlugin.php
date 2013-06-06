<?php


class TzPlugin {

    public static $pluginsList;

    private static function getPluginsList() {
        self::$pluginsList = Spyc::YAMLLoad( ROOT.'/plugins/pluginslist.yml' ); // set all the plugins defined in pluginslist.yml
    }

    public static function getPlugin( $pluginName, array $params = array() ) {
        if ( is_null( self::$pluginsList ) ) {
            self::getPluginsList(); // sets all the plugins defined in pluginslist.yml
        }

        if ( self::$pluginsList && array_key_exists( $pluginName, self::$pluginsList ) && is_file( ROOT.'/plugins/'.$pluginName.'/'.$pluginName.'.php' ) ) {
            require_once ROOT.'/plugins/'.$pluginName.'/'.$pluginName.'.php'; // includes the class
            if ( count( $params ) ) {
                $instance = new $pluginName( $params ); // the class needs parameters
            }
            else {
                $instance = new $pluginName(); // the class does not need parameters
            }
            if ( isset( $instance ) ) {
                return $instance;
            }
        }
        return FALSE; // THE PLUGIN DOES NOT EXIST OR IS NOT INITIALISED IN pluginslist.yml
    }
}
