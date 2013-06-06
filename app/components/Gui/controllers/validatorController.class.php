<?php
/**
* 
*/
class Validator
{
	private static $error = array();

	public static function checkInput (array $value) {
		var_dump($value);
	}

	public static function testDbConnect ($user, $pwd, $adress, $name) {

		try {
		    $tzPDO = new PDO('mysql:host='.$adress.';dbname='.$name, $user, $pwd);
		    $tzPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		   	return true;
		}
		catch(PDOException $e){
			self::$error['connectDb'] = 'Erreur durant la connexion à la base de données. Vérifier vos identifiants.';
			return false;
		}
	}

	public static function checkTpl ($post) {
		// Accepted value
		$conform = array('php', 'twig', 'smarty');

		if(!in_array($post, $conform)) {
			self::$error['tpl'] = 'Erreur dans le choix du moteur de template';
			//return false;
		}
		return true;
	}

	public static function checkRoute ($post) {
		// Accepted value
		$conform = array('yml', 'php');

		if(!in_array($post, $conform)) {
			self::$error['route'] = 'Erreur dans le choix du langage pour les routes';
			//return false;
		}
		return true;
	}

	public static function checkDb (array $post) {
		
		// check if a least one input isn't empty
		if(!empty($post['user']) || !empty($post['adress']) || !empty($post['name'])) {
			// check that all input are fill up
			if(!empty($post['user']) && !empty($post['adress']) && !empty($post['name']))
			{
				return true;

			} else {
				// check and store which input are not fill up in error array
				foreach ($post as $key => $value) {
					self::$error[$key."_value"] = $value;
					if(empty($value)) {
						self::$error[$key] = 'Vous devez remplir ce champ si vous voulez utiliser une BDD.';
					}
				}
				return false;
			}
		} else {
			// noting is fill up
			return true;
		}
	}

	public static function CleanPage($pages) {
		$arrayFiler = array_filter($pages);
		return $arrayFiler;
	}

	public static function getError(){
		return self::$error;
	}
	
}
?>