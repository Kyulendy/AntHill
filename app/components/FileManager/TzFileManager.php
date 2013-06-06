<?php

class TzFileManager
{
	private $currentPath;
	private $root;
	private $item_info;
	private $error;
	
	public function __construct ($root_param)
	{
		$this->root = $root_param;
		$this->error = "";
	}
	
	public function set_currentItem ($path = "../../")
	{
		if (is_dir($path) || is_file($path))
			$this->currentPath = $path;
		else
		{
			$this->error = "L'&eacutel&eacute;ment choisis n'est pas valide.";
			return FALSE;
		}
	}
	
	public function get_lastError()
	{
		if (!empty($this->error))
			return $this->error;
		else
			return FALSE;
	}
	
	public function get_root()
	{
		return $this->root;
	}
	
	public function get_itemInfos()
	{
		$i = 0;
		if (is_file($this->currentPath))
		{
			$fp = $this->xfopen("r");
			$stats = fstat($fp);
			$this->xfclose($fp);
			$infos["Type"] = "Fichier";
			$infos["Size"] = $stats["size"];
		}
		else if (is_dir($this->currentPath))
		{
			$dp = $this->xopendir($this->currentPath);
			while($Entry = $this->xreaddir($dp))
				$i++;
			$infos["Size"] = $i;
			$infos["Type"] = "Dossier";
		}
		$infos["Name"] = basename($this->currentPath);
		$infos["Path"] = dirname($this->currentPath);
		return $infos;
	}
	
	public function get_currentItem()
	{
		return($this->currentPath);
	}
	
	private function xfopen($mode)
	{
		if (file_exists($this->currentPath) && is_file($this->currentPath))
		{
			$handle = fopen($this->currentPath, $mode);
			if ($handle == false)
			{
				$this->error = "Impossible d'ouvrir le fichier.";
				return FALSE;
			}
			else
				return ($handle);
		}
		else
		{
			$this->error = "L'&eacutel&eacute;ment choisis n'est pas valide.";
			return FALSE;
		}
	}

	private function xfread($handle, $lenght)
	{
		$lenght = $lenght + 1;
		if ($handle != false)
		{
			$lenght = ($lenght*$lenght)/$lenght;
			$buffer = fread($handle, $lenght);
			if ($buffer == false)
			{
				$this->error = "Impossible de lire le fichier.";
				return FALSE;
			}
			else
				return ($buffer);
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}
	
	public function get_fileContent()
	{
		$handle = $this->xfopen("r+");
		$return = $this->xfread($handle, filesize($this->currentPath)*10);
		$this->xfclose($handle);
		return $return;
	}
	
	public function replace_fileContent($str)
	{
		$handle = $this->xfopen("w+");
		$return = $this->xfwrite($handle, $str);
		$this->xfclose($handle);
		return $return;
	}
	
	public function add_fileContent($str)
	{
		$handle = $this->xfopen("a");
		$return = $this->xfwrite($handle, $str);
		$this->xfclose($handle);
		return $return;
	}
	
	private function xfwrite ($handle, $str)
	{
		if ($handle != false && $str != null)
		{
			$new_chars = fwrite($handle, $str);
			if ($new_chars != false)
				return ($new_chars);
			else
			{
				$this->error = "Echec de l'&eacute;criture dans le fichier.";
				return FALSE;
			}
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;	
		}
	}
	
	private function xfclose ($handle)
	{
		if ($handle != false)
		{
			$return = fclose($handle);
			if ($return == false)
			{
				$this->error = "Echec de la fermeture du fichier.";
				return FALSE;
			}
			else
				return ($return);
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}
	
	public function xrename ($newname)
	{
		if (file_exists($this->currentPath) || is_dir($this->currentPath))
		{
			$return = rename($this->currentPath, $newname);
			if ($return == false)
			{
				$this->error = "L'op&eacute;ration de rennomage a &eacute;chou&eacute;.";
				return false;
			}
			else
			{
				$this->set_currentItem($newname);
				return true;
			}
		}
		else
		{
			$this->error = "L'&eacutel&eacute;ment choisis n'est pas valide.";
			return false;
		}
	}
	
	private function xopendir()
	{
		if (file_exists($this->currentPath) && is_dir($this->currentPath))
		{
			$handle = opendir($this->currentPath);
			if ($handle == false)
			{
				$this->error = "Probl&egrave;me lors de l'ouverture du dossier.";
				return FALSE;
			}
			else
				return ($handle);
		}
		else
		{
			$this->error = "L'&eacutel&eacute;ment choisis n'est pas valide.";
			return FALSE;
		}
	}

	public function xreaddir($handle)
	{
		if ($handle != false)
		{
			return (readdir($handle));
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}

	private function xclosedir($handle)
	{
		if ($handle != null)
		{
			$return = closedir($handle);
			if ($return == false)
			{
				$this->error = "Impossible de fermer cet &eacute;l&eacute;ment.";
				return FALSE;
			}
			else
				return ($return);
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}

	public function  ls()
	{
		$tab = array();
		$handle = $this->xopendir($this->currentPath);
		while ($contents = $this->xreaddir($handle))
		{
			array_push($tab, $contents);
		}
		return $tab;
	}
	
	private function xrmdir ($handle)
	{
		if ($handle != false)
		{
			$return = rmdir($handle);
			if ($return == false)
			{
				$this->error = "La suppression du dossier a eacute;chou&eacute;";
				return FALSE;
			}
			else
				return ($return);
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}
	
	private function xunlink ($handle)
	{
		if ($handle != false)
		{
			$return = unlink($handle);
			if ($return == false)
			{
				$this->error = "La suppression du fichier a &eacute;chou&eacute;";
				return FALSE;
			}
			else
				return ($return);
		}
		else
		{
			$this->error = "La ressource fournie n'est pas valide.";
			return FALSE;
		}
	}
	
	public function fDelete ()
	{
			
		$return = unlink($this->currentPath);
	
		if ($return == false)
		{
			$this->error = "La suppression du fichier a &eacute;chou&eacute;";
			return FALSE;
		}
	}
	
	public function delete_all($empty = FALSE)
	{
	    if(substr($this->currentPath,-1) == "/")
	        $this->currentPath = substr($this->currentPath,0,-1);
	    if(!file_exists($this->currentPath) || !is_dir($this->currentPath))
	        return FALSE;
		else
		{
	        $directoryHandle = $this->xopendir($this->currentPath);
	        while ($contents = $this->xreaddir($directoryHandle))
			{
				if ($contents === FALSE)
				{
					$this->error = "Erreur lors de la lecture du dossier";
					return FALSE;
				}
	            if($contents != '.' && $contents != '..')
				{
	                $path = $this->currentPath . "/" . $contents;
	                if(is_dir($path))
	                    $this->delete_all($path);
	                else
	                    $this->xunlink($path);
	            }
	        }
	        $this->xclosedir($directoryHandle);
	        if($empty == FALSE)
			{
	            if(!$this->xrmdir($this->currentPath))
	                return FALSE;
	        }
	        return true;
	    }
	}
	
	public function xmkdir ($name)
	{
		if (file_exists($this->currentPath."/".$name."/") || is_dir($this->currentPath."/".$name."/"))
		{
			$this->error = "Le dossier existe d&eacute;j&agrave; dans ce r&eacute;pertoire.";
			return FALSE;
		}
		else
		{
			$return = mkdir($this->currentPath."/".$name."/");
			if ($return == false)
			{
				$this->error = "Erreur lors de la cr&eacute;ation du dossier";
				return FALSE;
			}
			else
				return ($return);
		}
	}
	
	
	
	public function empty_dir($empty = FALSE)
	{
		if (is_dir($this->currentPath))
		{
		    if(substr($this->currentPath,-1) == "/")
		        $this->currentPath = substr($this->currentPath,0,-1);
		    if(!file_exists($this->currentPath) || !is_dir($this->currentPath))
		        return FALSE;
			else
			{
		        $directoryHandle = $this->xopendir($this->currentPath);
		        while ($contents = $this->xreaddir($directoryHandle))
				{
					if ($contents === FALSE)
					{
						$this->error = "Erreur lors de la lecture du dossier";
						return FALSE;
					}
		            if($contents != '.' && $contents != '..')
					{
		                $path = $this->currentPath . "/" . $contents;
		                if(is_dir($path))
		                    $this->empty_dir($path);
		                else
		                    $this->xunlink($path);
		            }
		        }
		        $this->xclosedir($directoryHandle);
		        return true;
		    }
		}
		else
		{
			$this->error = "Erreur lors de la lecture du dossier";
			return FALSE;
		}
	}

	public function xtouch ($name)
	{
		if (file_exists($this->currentPath . "/" . $name) || is_dir($this->currentPath . "/" . $name))
		{
			$this->error = "Le fichier existe d&eacute;j&agrave;";
			return FALSE;
		}
		else
		{
			$return = touch($this->currentPath . "/" . $name);
			if ($return == false)
			{
				$this->error = "Erreur lors de la cr&eacute;ation du fichier";
				return FALSE;
			}
			else
				return ($return);
		}
	}
	
	public function list_all($path = "", $i = "1")
	{
		if (is_dir($this->currentPath))
		{
			if ($i == "1")
			{
				$path = $this->currentPath;
				$i = "0";
			}
		  	if ($dir = opendir($path))
		  	{
		  		$tab = array();
		    	while($file = $this->xreaddir($dir))
		    	{
		    		if (is_dir($path."/".$file))
		      			$tab[] = strtoupper($file);
					else
						$tab[] = $file;
		      		if(is_dir($path."/".$file)  && !in_array($file, array('.', '..')))
		      		array_push ($tab, $this->list_all($path."/".$file, "0"));
		    	}
		    	$this->xclosedir($dir);
		  	}
			return $tab;
		}
		else
		{
			$this->error = "Vous devez choisir un dossier et pas un fichier";
			return FALSE;
		}	
	}
	
	public function xcopy($newfile)
	{
		if (file_exists($this->currentPath))
		{
			$return = copy($this->currentPath, $newfile);
			if ($return == false)
			{
				$this->error = "Erreur lors de la copie du dossier";
				return (false);
			}
			else
				return ($return);
		}
		else
		{
			$this->error = "L'element courrant est invalides";
			return (false);
		}
	}
	
	public function xcopydir($dir_paste)
	{
		if (is_dir($this->currentPath))
		{
            if ($dh = opendir($this->currentPath))
			{     
                while (($file = readdir($dh)) !== false)
				{
                    if (!is_dir($dir_paste)) mkdir ($dir_paste, 0777);
						if(is_dir($this->currentPath.$file) && $file != '..'  && $file != '.')
							copy_dir ($this->currentPath.$file.'/' , $dir_paste.$file.'/' );
						elseif($file != '..'  && $file != '.') copy ( $this->currentPath.$file , $dir_paste.$file );
                }
				closedir($dh);
            }
		}                
	}

	public function fCopy($origin, $destinataire) {
			if (!copy($origin, $destinataire)) {
    			$this->error = "La copie  du fichier a échoué...\n";
			}

		
	}
}


?>