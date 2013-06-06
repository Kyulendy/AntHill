<?php

class GeneratorController extends TzController{

	public function generateEntityAction() {

		$objPDO = TzSQL::getPDO();
		
		if (!is_null($objPDO)) {
			$obj = $objPDO->prepare('show tables');
			$obj->execute();
			$tables = $obj->fetchAll();

			$columsList = array();
			foreach ($tables as $key => $value) {
				$colums = $objPDO->prepare('show columns from '.$value[0]);
				$colums->execute();
				$columsList[$value[0]] = $colums->fetchAll(PDO::FETCH_ASSOC);
			}
			#var_dump($columsList);
			if(empty($_POST['generateEntity']) && empty($_POST['tablename'])) {
				require_once ROOT.'/app/components/Gui/views/entityGeneratorForm.php';
			}
			else{
				require_once(ROOT.'/app/components/Gui/includes/entityGenerator.php');

				$results = createEntity($_POST['tablename']);

				if(!empty($results))
					require_once(ROOT.'/app/components/Gui/views/entityGeneratorResults.php');
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}
		} else {
			Header("Location:". WEB_PATH);
		}
		
	}
}