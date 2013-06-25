<?php
/**
 * This class is call by /src/config/routing.yml
 * when no parameters are passed.
 * You can change is behavior, do what you want.
 */

use Components\Controller\TzController;

class defaultController extends TzController {

	// first method call when the website is launched
	public function showAction () {
		$this->tzRender->setPage('Default');
		$this->tzRender->run('layout');
	}
}