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
					    $data = array('id' =>$user->getId_user(),
					    			'login'   => $login,
			                		'firstname' => $user->getFirst_name(),
			                		'lastname' => $user->getLast_name());
			  			//TzAuth::addUserSession($data); // ?
					    $this->tzRender->run('/templates/dashboard', $data);
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
		if (isset($_POST["register"])) {
		//Request sent
			if ((empty($_POST['login']))||(empty($_POST['password']))||(empty($_POST['repeat_password']))||(empty($_POST['firstname']))||(empty($_POST['lastname'])) ) {
			    $this->tzRender->run('/templates/register', array('error' => "Fill all the fields please"));
			} else if (($_POST['password'])!=($_POST['repeat_password'])) {
			    $this->tzRender->run('/templates/register', array('error' => "Password and password confirmation don't match"));
			} else if (filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
			    $this->tzRender->run('/templates/register', array('error' => "Please enter a valid email"));
			} else {
				$user = tzSQL::getEntity('users');
				$user->findOneBy("email", $_POST['login']);
				if (!empty($user)) {
				    $this->tzRender->run('/templates/register', array('error' => "This login is already taken"));
				} else {
					//Register
					$usersEntity = tzSQL::getEntity('users');
					$usersEntity->setEmail($_POST['login']);
					$usersEntity->setPassword(TzAuth::encryptPwd($_POST['password']));
					$usersEntity->setFirst_name($_POST['firstname']);
					$usersEntity->setLast_name($_POST['lastname']);
					$usersEntity->Insert();
				    $this->tzRender->run('/templates/login', array('success' => "You have been registered. Please log in."));
				}
			}
		} else {
			$this->tzRender->run('/templates/register');
		}
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