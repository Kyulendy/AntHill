<?php 

class sampleController extends TzController {
	 public function showAction () {
	 	$this->tzRender->run('/templates/sample');
	}
}
