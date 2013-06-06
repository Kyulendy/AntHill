<?php

class ErrorManagerExtend {

	private $logFile 				= "error.log";
	private $logPath;
	private $displayError;
	private $saveInLog;
	private $arrayOfError 			= array();
	private $numberOfcurrentError 	= 0;
	private $phpFatalErrorsCode 	= array(1,4,16,64,256,4096);
	private $templateHTMLError 		= array();

	public function __construct (array $params) {
        $this->logPath 		= $params['error']['path'];
		$this->saveInLog 	= $params['error']['save'];
		$this->displayError = $params['toolbar']['display'];
	}

	/**
	 * [initExtendError description]
	 * @param  array  $error current error in the script
	 * @return void         
	 */
	public function initExtendError (array $error) {
		if ($this->saveInLog) {
			$this->save($error);
		}
		if ($this->displayError) {
			// foreach ($error as $singleError) {
			// 	$this->errorTpl($singleError);
			// }
		}

        // // Manage case if error is fatal
        // if (in_array($error['type'], $this->phpFatalErrorsCode)) {
        //     $this->save($error);
        //     $this->errorTpl($error);
        // }
	}

    /**
     * Save the error in the log file
     * @param array $params
     */
    public function save(array $params){
		$line = '';
		$i = 1;
		foreach ($params as $array) {

			foreach ($array as $key => $value) {
				if ($i < 5) {
					$line .= $key .'=>'.str_replace("\n"," ",$value)."\t";
				} else {
					$line .= $key .'=>'.str_replace("\n"," ",$value);
				}
				$i++;	
			}
			$line .= "\n";
			$i = 1;
		}
		// write error in the log file
		@file_put_contents($this->logPath.$this->logFile, $line, FILE_APPEND);
	}

	/************************************************
	* templates
	************************************************/

	/**
	 * Template that is going to be displayed
	 * @param  array  $error  Store type, line, message, date, trace, code
	 * @return void
	 */
	public function errorTpl(array $error) {

		if (in_array($error['type'], $this->phpFatalErrorsCode)) {
			$store = '<div class="tiitz-error-popup" style="width:100%;color: #000;background-color: #F2DEDE;border-color: #EED3D7;margin: 0px; padding-left:8px;padding-right:8px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;margin:auto !important;font-size:14px;">';
		} else {
			$store = '<div class="tiitz-error-popup" style="color: #000;background-color: #FCF8E3;border : 1px solid #FBEED5;margin: 0px; padding-left:8px;padding-right:8px;font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;margin:auto !important;">';
		}

		$store .= '	<ul style="list-style-type:none;margin: 0px;padding:5px;margin:auto !important;">
					<h4 style="margin:5px 0px;font-size:16px">';
		(isset($error['type']) && in_array($error['type'], $this->phpFatalErrorsCode)) ? $store .= "Erreur Fatale" : 
																						  $store .= "Erreur durant l'execution du script";
		$store .= '</h4>';
		// loop through error array
		foreach ($error as $key => $value) {
			if(is_array($value)) {
				$this->errorTpl($value);
			} else {
				$store .= '<li><strong style="font-size:14px">["'.$key.'"]</strong> : <span style="font-size:13px">'.$value.'</span></li>';
			}
		}
		
		$store .= '</ul></div>';
		
		$this->phpCodeTpl($store, $error['file'], $error['line'],$error['type']);
	}

	public function phpCodeTpl($output, $filename, $lineError, $errorType) {

	    if(file_exists($filename) && is_file($filename)) { 

            // display only 10 lines before and after the error.
            $beginErrorDisplay  = ($lineError > 10) ? ($lineError - 10) : $lineError;
            $endErrorDisplay    = $lineError + 10;

	        $output .= '<pre  class="accordion" id="accordion2" style="margin: 0px auto;padding: 0px 8px;"><code><span style="color: '.ini_get('highlight.html').';font-size: 12px;">'; 
	        
	        $code = substr(highlight_file($filename, true), 36, -15); 
	        $start_line = 1; 
	        $lines = explode('<br />', $code); 
	       
	        $chr_lines = count($lines); 
	        $chr_lines = strlen($chr_lines); 
	        
			$output .= '<div id="collapse" class="accordion-body collapse">';
	        foreach($lines as $line) 
	        {
                if($start_line >= $beginErrorDisplay && $start_line < $endErrorDisplay) {
                    $output .= "<p style='margin:auto;'";
                    if ($start_line%2 == 0) {
                        $output .= "background-color:#f1f1f1;'";
                    }
                    $output .= ">";
                    $nline = str_pad($start_line, $chr_lines, ' ', STR_PAD_LEFT);
                    if($lineError == $start_line) {
                        $output .= '<span style="color: #f1f1f1; background-color: red;" class="php_highlight_line">'.$nline. ': '.$line."</span>\n";
                    }  else {
                        $output .= '<span style="color: grey;" class="php_highlight_line">'.$nline. ':</span> '.$line."\n";
                    }
                    $output .= "</p>";
                }
		        $start_line ++; 
		    } 
	        $output	.= '</div>';
	        $output .= '</span></code></pre>'; 
	        
	        $this->numberOfcurrentError++;

        	// array_push($this->templateHTMLError, $output);
        	// when a fatal error occur, the toobar can't be display
        	// so we print directly the message error send by php
        	// if (in_array($errorType, $this->phpFatalErrorsCode)) {
        		print $output;
        	// }
		} 
	} 

	/**************************************
	* put log into a array format
	**************************************/

	/**
	 * format all error from the log file in a array
	 * @return void
	 */
	private function arrayFormatError() {
		$error = array();
		try { 
		$handle = @fopen($this->logPath.$this->logFile, "rb");
			
			if ($handle) {
				while (false !== ($line = fgets($handle))) {
					// We need to remove \n from the array
					$line 		= str_replace("\n","|",$line);
					$newEntry 	= explode("\t", $line);
					$i = 1;
					
					foreach ($newEntry as $key => $value) {
						$current = explode('=>', $value);
						if($i < 5) {
							$error[$current[0]] = $current[1];

						} else {
							$error[$current[0]] = substr($current[1],0,-1);
						}
						$i++;
					}
					$i = 1;
					array_push($this->arrayOfError, $error);				
					unset($error);
		      	}
			} else {
				throw new Exception("Error Processing Request", 1);
			}
		} catch(Exception $e) {
			DebugTool::$error->catchError($e);
		}
	   	$this->arrayOfError = array_reverse($this->arrayOfError);
	}

	/***************************************
	* getter
	****************************************/

	public function getArrayOfError() {
		$this->arrayFormatError();
		return $this->arrayOfError;
	}
	public function getJsonExportOfError() {
		$this->arrayFormatError();
		return json_encode($this->arrayOfError);	
	}
	public function getTemplateHTMLError () {
		return $this->templateHTMLError;
	}
	public function getNumberOfcurrentError(){
		return $this->numberOfcurrentError;
	}

}