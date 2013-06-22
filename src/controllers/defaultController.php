<?php
/**
 * This class is call by /src/config/routing.yml
 * when no parameters are passed.
 * You can change is behavior, do what you want.
 */
class defaultController extends TzController {

	// First method call when the website is launched
	public function showAction () {
		  if(TzAuth::isUserLoggedIn()){
		    echo 'Connécté';
			$this->tzRender->run('/templates/dashboard');
		  } else {
		    echo 'Non connecté';
			$this->tzRender->run('/templates/login');		  	
		  }
		//$this->tzRender->run('/templates/default');                                                        
	}

	// Login attempt
	public function loginAction() {
		if (TzAuth::isUserLoggedIn()) {
			$this->dashboardAction();
		} else {
			if (!isset($_POST["login"])) {
				$this->tzRender->run('/templates/login');
			} else {
				if ( (empty($_POST['login']))||(empty($_POST['password'])) ) {
				//Not filled
				    $this->tzRender->run('/templates/login', array('error' => "Fill all the fields please"));
				} else {
				//All filled - testing
					$login = $_POST['login'];
					$password = $_POST['password'];
					$userArray = array('password' => $password,
		                   			'email' => $login);
					//die(TzAuth::encryptPwd("password"));
					//die(TzAuth::getSalt());
					if(TzAuth::login($userArray)) {
						// Connected
					    echo 'Connecté !!';
					    //Fetch entity to get name and lastname
					    $user = tzSQL::getEntity('users');
	    				$user->findOneBy("email", $login);
					    $data = array('login'   => $login,
			                		'firstname' => $user->getFirst_name(),
			                		'lastname' => $user->getLast_name());
					    //die(var_dump($data));
			  			//TzAuth::addUserSession($data); // ?
					    $this->tzRender->run('/templates/dashboard');
					    //dashb - inside it use sessions names and stuff
					} else {
						//Connection error
					    echo 'Erreur de connexion !!';
				    	$this->tzRender->run('/templates/login', array('error' => "Wrong login/password combination. Please try again"));
					}
				}
			}
		}
	}

	// Register
	public function registerAction () {
		if(TzAuth::isUserLoggedIn())
			TzAuth::logout();
		$this->tzRender->run('/templates/register');
	}

	// Logout
	public function logoutAction () {
		if(TzAuth::isUserLoggedIn())
			TzAuth::logout();
		$this->tzRender->run('/templates/login');
	}

	// Logout
	public function dashboardAction () {
		if(!TzAuth::isUserLoggedIn())
			$this->loginAction();
		else
			//More to come
			$this->tzRender->run('/templates/dashboard');
	}
}