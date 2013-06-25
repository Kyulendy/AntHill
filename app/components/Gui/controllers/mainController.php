<?php
/**
* 
*/
use Components\Controller\TzController;
use Components\FileManager\TzFileManager;
use Components\Spyc\Spyc;
require_once 'validatorController.class.php';

class MainController extends TzController {

	private $routingExtension;
	private $extension;

	public function checkAction() {
	
		if (isset($_POST["firstConfig"]))
		{
			// relauch test
			Validator::checkTpl($_POST['tpl']);
			Validator::checkRoute($_POST['routesLang']);
			$res = Validator::checkDb(array('user' => $_POST['user'], 'pwd' => $_POST['pwd'],'adress' => $_POST['adress'], 'name' => $_POST['name']));

			if ($res == true) {
				// test db connection
				Validator::testDbConnect($_POST['user'], $_POST['pwd'],$_POST['adress'], $_POST['name']);
			}
		

			$_POST["pages"] = Validator::CleanPage($_POST["pages"]);
			// check if there error array is empty or no	
			$error = Validator::getError();
			
			if(empty($error)) {
				
				$this -> routingExtension = $_POST['routesLang'];
				$this -> templateEngine();
				$this -> configGenerator();
				$this -> pagesGenerator(); 
				// redirect /web/index.php which will redirect to /src/config/routing.yml
				header('location:'.WEB_PATH.'/index.php');
			} else {
				require_once("../app/Components/Gui/views/_index.php");
			}
		} else {
			require_once("../app/Components/Gui/views/_index.php");
		}

	}

	private function templateEngine() {
		if ($_POST['tpl'] == 'smarty') {
			$this->extension = 'tpl';
		} elseif ($_POST['tpl'] == 'twig') {
			$this->extension = 'html.twig';
		} else {
			$this->extension = $_POST['tpl'];
		}
		
	}

	private function configGenerator()
	{
		// File manager class
		$fm = new TzFileManager(ROOT);

		// location of the file config.yml
		$fm->set_currentItem(ROOT."/app/config/config.yml");

		// write route language
		$fm->replace_fileContent("# Routing\nroutingType:\t".$_POST["routesLang"]);
		// write template engine
		$fm->add_fileContent("\n\n# Template Engine\ntemplate:    ".$_POST["tpl"]);
		// write db parameters
		$fm->add_fileContent("\n\n# database configuration\ndatabase:\n    user:\t\t".$_POST["user"]."\n    password:\t\t".$_POST["pwd"]."\n    dbname:\t\t".$_POST["name"]."\n    host:\t\t".$_POST["adress"]."\n    engine:\t\tmysql");
		// add others parameters
		$fm->add_fileContent("\n\n# language (tool development)\nlanguage:    fr\n\n# environnement (dev | prod)\nenvironnement:    dev\n\n# Permit to check if a project is already started\nexistingproject:    true");
		
	}	

	private function pagesGenerator()
	{
		// File manager class
		$fm = new TzFileManager(ROOT);
		$fm->set_currentItem(ROOT."/src/config/");
		require_once(ROOT.'/app/Components/Spyc/Spyc.php');
		$config = Spyc::YAMLLoad(ROOT.'/app/config/config.yml');
		$this -> routingExtension = $config['routingType'];
		if ($config['template'] == 'smarty') {
			$this->extension = 'tpl';
		} elseif ($config['template'] == 'twig') {
			$this->extension = 'html.twig';
		} else {
			$this->extension = $config['template'];
		}
		// create routing file
		if (!file_exists(ROOT."/src/config/routing.".$this -> routingExtension))
		{
			$fm->xtouch("routing.".$this -> routingExtension);
			$fm->set_currentItem(ROOT."/src/config/routing.".$this -> routingExtension);
			if ($this -> routingExtension === "yml")
				$fm->add_fileContent("\ndefault_show:\n\tpattern:\t / \n\tcontroller: default:show");
			if ($this -> routingExtension === "php")
				$fm->replace_fileContent('<?php'."\n\t".'$tzRoute = array('."\n\n\t\t".'"default_show" => array('."\n\t\t\t".'"pattern" => "/",'."\n\t\t\t".'"controller" => "default:show" ),'."\n");
			// layout template
			$fm->fCopy(ROOT."/app/Components/template/views/defaultView/layout.".$this->extension, ROOT."/src/views/layout.".$this->extension);
			$fm->fCopy(ROOT."/app/Components/template/views/defaultView/templates/default.".$this->extension, ROOT."/src/views/templates/default.".$this->extension);
			// default controller
			if ($this->extension == "html.twig") {
				$fm->fCopy(ROOT."/app/Components/template/controllers/twigDefaultController.php", ROOT."/src/controllers/defaultController.php");
			} else {
				$fm->fCopy(ROOT."/app/Components/template/controllers/phpDefaultController.php", ROOT."/src/controllers/defaultController.php");
			}
			$firstEdit = true;
		}
		
		// add pages if user has tried to create one or more
		if (isset($_POST["pages"]) && !empty($_POST["pages"])) {
			
			$pages = $_POST['pages'];
			foreach ($pages as $page) {
				$page = str_replace( "\r", "", $page);
				$page = strtolower($page);
				// create routing.yml
				$fm->set_currentItem(ROOT."/src/config/routing.".$this -> routingExtension);
				if ($this -> routingExtension === "yml")
					$fm->add_fileContent("\n".$page."_show:\n\tpattern:\t/".$page."\n\tcontroller: ".$page.":show ");
				if ($this -> routingExtension === "php")
				{
					//Reopening php array if it was closed
					if (!isset($firstEdit))
					{
						$handle = fopen(ROOT."/src/config/routing.php", 'r+');
						ftruncate($handle, filesize(ROOT."/src/config/routing.php") - 2);
						fclose($handle);
					}
					$fm->add_fileContent("\n\t\t".'"'.$page.'_show" => array('."\n\t\t\t".'"pattern" => "/'.$page.'",'."\n\t\t\t".'"controller" => "'.$page.':show" ),'."\n");
				}
				// create template file
				$fm->set_currentItem(ROOT."/src/views/templates");
				$fm->xtouch($page.'.'.$this->extension);
				// create additional controllers
				$fm->set_currentItem(ROOT."/src/controllers");
				$fm->xtouch($page."Controller.php");
				$fm->set_currentItem(ROOT."/src/controllers/".$page."Controller.php");
				$fm->add_fileContent("<?php \n\n use app\Components\Controller\TzController; \n\t class ".$page."Controller extends TzController {\n\t public function showAction () {\n\t\t echo 'Vous &ecirc;tes sur la page : ".$page."';\n\t}\n}\n");
			}
			
		} 
		// We need to close the php array
			if ($this -> routingExtension === "php")
			{
				$fm->set_currentItem(ROOT."/src/config/routing.php");
				$fm->add_fileContent(");");
			}
	}

	public function endInstallationAction() {

		$ext = $this->conf["template"];

		if ($ext == 'smarty') {
			$this->extension = 'tpl';
		} elseif ($ext == 'twig') {
			$this->extension = 'html.twig';
		} else {
			$this->extension = $ext;
		}

		$fm = new TzFileManager(ROOT);

		// We set the default controller and delete it
		$fm->set_currentItem(ROOT."/src/controllers/defaultController.php");
		$fm->fDelete();

		// We set the app controller and copy it
		if ($this->extension == "html.twig")
			$fm->fCopy(ROOT."/app/Components/template/controllers/twigDefaultController.php", ROOT."/src/controllers/defaultController.php");
		else
			$fm->fCopy(ROOT."/app/Components/template/controllers/phpDefaultController.php", ROOT."/src/controllers/defaultController.php");

		// We set the src layout and delete it
		$fm->set_currentItem(ROOT."/src/views/layout.".$this->extension);
		$fm->fDelete();

		// We set the app layout and copy it
		$fm->fCopy(ROOT."/app/Components/template/views/clearView/layout.".$this->extension, ROOT."/src/views/layout.".$this->extension);

		// We set the src defaultTemplate and delete it
		$fm->set_currentItem(ROOT."/src/views/templates/default.".$this->extension);
		$fm->fDelete();

		// We set the defaultTemplate and copy it
		$fm->fCopy(ROOT."/app/Components/template/views/clearView/templates/default.".$this->extension, ROOT."/src/views/templates/default.".$this->extension);

		Header("Location:". WEB_PATH);
	}

	private function configUpdate()
	{
		$fm = new TzFileManager(ROOT);
		$fm->set_currentItem(ROOT."/app/config/config.yml");
		require_once(ROOT.'/app/Components/Spyc/Spyc.php');
		$config = Spyc::YAMLLoad(ROOT.'/app/config/config.yml');
		$config['database']['user'] = $_POST['user'];
		$config['database']['password'] = $_POST['pwd'];
		$config['database']['host'] = $_POST['adress'];
		$config['database']['dbname'] = $_POST['name'];
		$fm->replace_fileContent(Spyc::YAMLDump($config));
	}

	public function checkEditAction() {
		if (isset($_POST["firstConfig"]))
		{
			// relauch test
			$res = Validator::checkDb(array('user' => $_POST['user'], 'pwd' => $_POST['pwd'],'adress' => $_POST['adress'], 'name' => $_POST['name']));
			if ($res == true) {
				// test db connection
				Validator::testDbConnect($_POST['user'], $_POST['pwd'],$_POST['adress'], $_POST['name']);
			}

			$_POST["pages"] = Validator::CleanPage($_POST["pages"]);
			// check if there error array is empty or no
			$error = Validator::getError();

			if(empty($error)) {
				$this -> configUpdate();
				$this -> pagesGenerator();
				// redirect /web/index.php which will redirect to /src/config/routing.yml
				header('location:'.WEB_PATH.'/index.php');
				} else {
				require_once("../app/Components/gui/views/_indexEdit.php");
			}
		} 
		else {
			require_once("../app/Components/gui/views/_indexEdit.php");
		}

	}



}
