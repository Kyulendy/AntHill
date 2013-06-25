<?php
$start = microtime(true);
require_once "../vendors/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php";
require '../vendors/autoload.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->useIncludePath(true);
$loader->registerNamespaces(array(
 
 	"Components" => __DIR__."/../app",
 	"src" => __DIR__."/..",
 	"plugins" => __DIR__."/..",

	));

$loader->register();

// We execute the kernel and start TiiTz
Components\Kernel\TzKernel::execute();

// toolbar for development environment
if(Components\Kernel\Tzkernel::$tzConf['environnement'] == 'dev') {
	// Calcul time loading page
	Components\DebugTools\DebugTool::$toolbar->setTimeLoadingPage(number_format((microtime(true) - $start),4));
	// process of managing error
	Components\DebugTools\DebugTool::$errorExtend->initExtendError(Components\DebugTools\DebugTool::$error->getError());
	// load Toolbar and display it
	require_once Components\DebugTools\DebugTool::$toolbar->getPathToToolbar();
}