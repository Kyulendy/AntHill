<?php
$start = microtime(true);
require_once("../app/tzKernel.php");
tzKernel::execute();

// toolbar for development environment
if(tzkernel::$tzConf['environnement'] == 'dev') {
	// Calcul time loading page
	DebugTool::$toolbar->setTimeLoadingPage(number_format((microtime(true) - $start),4));
	// process of managing error
	DebugTool::$errorExtend->initExtendError(DebugTool::$error->getError());
	// load Toolbar and display it
	require_once DebugTool::$toolbar->getPathToToolbar();
}