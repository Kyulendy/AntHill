<?php
/**
 * Allow compatibility between differents template engines : one methode for all
 */

class TzRender {
	
	private static $instance;
	// default path for the views in smarty and twig
	private static $path;
	// some defaults settings
	private $tpl 	= 'php';
	private $ext 	= 'php';
	private $cache 	= false;
	// prop & file when using PHP
	private $prop;
	private $file;
	// array that store object of either smarty or twig
	private $renderedPage = array();
	private static $page;
	
	private function __construct($tpl){
		
		switch ($tpl) {
			
			case 'twig':
				if (file_exists(ROOT.'/vendors/Twig-1.11.1/lib/Twig/Autoloader.php')) {
					require_once ROOT.'/vendors/Twig-1.11.1/lib/Twig/Autoloader.php';
					Twig_Autoloader::register();
					$loader = new Twig_Loader_Filesystem(self::$path);
					$this->renderedPage = new Twig_Environment($loader);
					$this->tpl = 'twig';
					$this->ext = 'html.twig';
				} else {
					DebugTool::$error->catchError(array('Failed loaded Twig', __FILE__,__LINE__, true));
				}
				break;

			case 'smarty':
				if (file_exists(ROOT.'/vendors/Smarty-3.1.12.2/libs/Smarty.class.php')) {
					require_once ROOT.'/vendors/Smarty-3.1.12.2/libs/Smarty.class.php';
					$this->renderedPage = new Smarty();
					$this->renderedPage->setTemplateDir(self::$path);
					$this->tpl = 'smarty';
					$this->ext = 'tpl';
				} else {
					DebugTool::$error->catchError(array('Failed loaded smarty', __FILE__,__LINE__, true));
				}
				break;				
		
			default:
				$this->ext = $this->tpl = 'php';
				break;
		}
	}

	// singleton
	public static function getInstance($tpl) {
		if (!is_null(self::$instance)) {
			return self::$instance;
		} else {
			// set the path for the view
			self::$path = ROOT.'/src/views/';
			self::$instance = new TzRender($tpl);
			return self::$instance;
		}
	}

	public function run($file , array $prop = null) {
			
		$this->fileExists($file);
		$prop['WEB_PATH'] = WEB_PATH;
		$prop['SESSION'] = $_SESSION;
		//return the template depending of the engine chosen by the user
		if($this->tpl === 'twig') {
			// display twig template
			if (is_null($prop)) {
				print $this->renderedPage->render($file.'.html.twig');
			} else {
				print $this->renderedPage->render($file.'.html.twig', $prop);
			}
			
		} elseif ($this->tpl === 'smarty') {
			// check if there are arguments pass to the method to avoid bug
			if ($prop !== null) {
				$this->renderedPage ->assign("prop", $prop);	
			}
			// display smarty template
			$this->renderedPage ->display($file.'.tpl');
		} else {
			$this->prop = $prop;
			$this->file = $file;
			foreach ($this->prop as $tzTMPName => $tzTMPValue) {
				$$tzTMPName = $tzTMPValue;
			}
			// if there is no template engine chosen, just make a require
			return require self::$path.$this->file.'.php';
		}
	}
	
	// check if the file pass through the method exist
	private function fileExists($file) {
		if (file_exists(self::$path.$file.'.'.$this->ext)) {
			return true;
		} else {
			DebugTool::$error->catchError(array('failed to load the file, check the path', __FILE__,__LINE__, true));
		}
	}

	// setter ; set the cache if it's true on config.yml
	/*public static function setCache($bool){
		die('ewdwdewed');
		$this->$cache = $bool;
		$this->renderedPage->setCacheDir(ROOT.'/app/cache');
	}*/

	// getter
	public function getTpl() {
		return $this->tpl;
	}
	public function getRender(){
		return $this->renderedPage;
	}
	public function getPath() {
		return self::$path;
	}
	public static function getPage() {
		return self::$page;
	}
	// setter
	public function setPage($page) {
		self::$page = $page;
	}
} 
