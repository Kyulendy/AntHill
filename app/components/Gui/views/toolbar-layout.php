<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>TiiTz Framework</title>

    <link href='<?php print WEB_PATH;?>/tiitz/css/bootstrap.css' rel='stylesheet' type='text/css' />
    <link href='<?php print WEB_PATH;?>/tiitz/css/style-toolbar.css' rel='stylesheet' type='text/css' />

    <script type="text/javascript" src="<?php print WEB_PATH;?>/tiitz/js/jquery-1.9.0.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
</head>
<body>
<div class="tiitz">
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span3 well">
                <!--Sidebar content-->
                <ul class="nav nav-list">
                    <li class="nav-header">Configurations Tiitz</li>
                    <li class="<?php if(isset($active) && ($active == 'config-prod')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/config-prod">config.yml</a></li>
                    <li class="<?php if(isset($active) && ($active == 'config-dev')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/config-dev">config_dev.yml</a></li>
                    <li class="nav-header">Fichiers</li>
                    <li class="<?php if(isset($active) && ($active == 'logs')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/log">logs</a></li>
                    <li class="nav-header">phpinfo</li>
                    <li class="<?php if(isset($active) && ($active == 'general')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/phpinfo/general">INFO_GENERAL</a></li>
                    <li class="<?php if(isset($active) && ($active == 'config')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/phpinfo/configuration">INFO_CONFIGURATION</a></li>
                    <li class="<?php if(isset($active) && ($active == 'module')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/phpinfo/module">INFO_MODULES</a></li>
                    <li class="<?php if(isset($active) && ($active == 'env')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/phpinfo/environnement">INFO_ENVIRONMENT</a></li>
                    <li class="<?php if(isset($active) && ($active == 'variable')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/phpinfo/variable">INFO_VARIABLES</a></li>
                    <li class="nav-header">Liens</li>
                    <li class="<?php if(isset($active) && ($active == 'generateur')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/entityGenerator">Générateur entités</a></li>
                    <li class="<?php if(isset($active) && ($active == 'acl')){ print 'active'; } ?>"><a href="<?php print WEB_PATH;?>/configTiitz/toolbar/acl/generate">Mise à jour des ACL</a></li>
                    <li class="<?php if(isset($active) && ($active == 'config-tiitz')){ print 'active'; } ?>"><a href="#">Configuration Titz</a></li>
                    <li class="nav-header">Divers</li>
                    <li class="<?php if(isset($active) && ($active == 'route')){ print 'active'; } ?>"><a href="#">Routes</a></li>
                </ul>
            </div>
            <div class="span9" style="margin-bottom: 50px">
                <!--Body content-->

                <div>
                    <?php if(PATH_TOOLBAR == 'log') {
                        include_once ROOT.'/app/components/Gui/views/toolbar/toolbar-logs.php';
                    } elseif(PATH_TOOLBAR == 'config-prod') {
                        include_once ROOT.'/app/components/Gui/views/toolbar/toolbar-config.php';
                    } elseif(PATH_TOOLBAR == 'config-dev') {
                        include_once ROOT.'/app/components/Gui/views/toolbar/toolbar-config-dev.php';
                    } elseif(PATH_TOOLBAR == 'acl') {
                        include_once ROOT.'/app/components/Gui/views/toolbar/toolbar-acl.php';
                    } else {
                        include_once ROOT.'/app/components/Gui/views/toolbar/toolbar-phpinfo.php';
                    }
                ?>    
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>