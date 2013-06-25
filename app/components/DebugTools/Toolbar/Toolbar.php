<?php
/**
 * Class Toolbar
 * All usefull informations needed to display
 */

namespace Components\DebugTools\Toolbar;
use Components\DebugTools\DebugTool;

class Toolbar {
	
	private $path 		= '/views/layout.php';
	private $phpIni 	= array();
	private $conf 		= array();
	private $route		= array();
	private $TimeLoadingPage;
	private $phpVersion;
	private $frameworkVersion;

	public function __construct($frameworkVersion, array $conf = null, array $route = null){
		$this->phpIni 			= ini_get_all(null, false);
		$this->conf 			= $conf;
		$this->route 			= $route;
		$this->phpVersion 		= phpversion();
		$this->frameworkVersion = $frameworkVersion;
	}

    /**
     * convert the output of phpinfo into an array
     * @param $type
     * @param bool $return
     * @return array|mixed
     */
    public function phpinfo_array($type = -1, $return=false){
        DebugTool::$error->stopError(false);
        ob_start();
        phpinfo($type);

        $pi = preg_replace(
            array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
                '#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
                "#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
                    .'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
                '#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
                '#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
                "# +#", '#<tr>#', '#</tr>#'),
            array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
                '<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
                    "\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
                '<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
                '<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
                    '<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
            ob_get_clean());

        $sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
        unset($sections[0]);

        $pi = array();
        foreach($sections as $section){
            $n = substr($section, 0, strpos($section, '</h2>'));
            preg_match_all(
                '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#',
                $section, $askapache, PREG_SET_ORDER);
            foreach($askapache as $m)
                $pi[$n][$m[1]]=(!isset($m[3])||$m[2]==$m[3])?$m[2]:array_slice($m,2);
        }
        DebugTool::$error->stopError(true);
        return ($return === false) ? print_r($pi) : $pi;
    }

    // getter
    public function getPhpVersion () {
		return $this->phpVersion;
	}
	public function getFrameworkVersion () {
		return $this->frameworkVersion;
	}
	public function getPhpIni () {
		return $this->phpIni;
	}
	public function getPhpIniByIndex ($index) {
		return $this->phpIni[$index];
	}
	public function getRoute() {
		return $this->route;
	}
	public function getConf() {
		return $this->conf;
	}
	public function getPathToToolbar() {
		return __DIR__.$this->path;
	}
	public function getTimeLoadingPage() {
		return $this->TimeLoadingPage;
	}
	// setter
	public function setFrameworkVersion ($version) {
		$this->frameworkVersion = $version;
	}
	public function setTimeLoadingPage ($duration) {
		$this->TimeLoadingPage = $duration;
	}
}