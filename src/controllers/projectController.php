<?php
use Components\Kernel\TzKernel;
use Components\Controller\TzController;
use Components\Auth\TzAuth;
use Components\SQLEntities\TzSQL;


class projectController extends TzController {
	// Create Project
	public function newAction () {
		  if(TzAuth::isUserLoggedIn()) {
			if (!empty($_POST)) {
				//Request sent
				if ((empty($_POST['title']))||(empty($_POST['content']))) {
				    $this->tzRender->run('/templates/newproject', array('error' => "Fill all the fields please"));
				    $this->tzRender->run('/templates/newproject', array('error' => "Please enter a valid email"));
				} else {
					// Create new project
					$project = tzSQL::getEntity('projects');
					$project->setTitle($_POST['title']);
					$project->setContent($_POST['content']);
					$project->Insert();
				    $this->tzRender->run('/templates/newproject', array('success' => "You have been registered. Please log in."));
				}
			} else {
				$this->tzRender->run('/templates/newproject', TzAuth::readSession());
			}
		  } else {
			$this->tzRender->run('/templates/newproject');		  	
		  }
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
			  			TzAuth::addSession($data); // ?
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
		if (TzAuth::isUserLoggedIn())
			$this->logout();
		//if (isset($_POST["register"])) { // didn't work
		if (!empty($_POST)) {
		//Request sent
			if ((empty($_POST['login']))||(empty($_POST['password']))||(empty($_POST['repeat_password']))||(empty($_POST['firstname']))||(empty($_POST['lastname'])) ) {
			    $this->tzRender->run('/templates/register', array('error' => "Fill all the fields please"));
			} else if (($_POST['password'])!=($_POST['repeat_password'])) {
			    $this->tzRender->run('/templates/register', array('error' => "Password and password confirmation don't match"));
			} else if (!filter_var($_POST['login'], FILTER_VALIDATE_EMAIL)) {
			    $this->tzRender->run('/templates/register', array('error' => "Please enter a valid email"));
			} else {
				$user = tzSQL::getEntity('users');
				//die(var_dump($user));
				$found = $user->findOneBy("email", $_POST['login']);
				if ($found != null) {
				    $this->tzRender->run('/templates/register', array('error' => "This login is already taken"));
				} else {
					//Register
					$usersEntity = $user;
					$usersEntity->setEmail($_POST['login']);
					$usersEntity->setPassword(TzAuth::encryptPwd($_POST['password']));
					$usersEntity->setFirst_name($_POST['firstname']);
					$usersEntity->setLast_name($_POST['lastname']);
					$usersEntity->Insert();
				    $this->tzRender->run('/templates/login', array('success' => "You have been registered. Please log in."));
				}
			}
		} else {
			//die("nothing sent");
			$this->tzRender->run('/templates/register');
		}
	}

	private function logout() {
		TzAuth::emptySessionData();
		TzAuth::logout();
	}

	// Logout
	public function logoutAction () {
		if(TzAuth::isUserLoggedIn()) {
			$this->logout();
		}
		$this->tzRender->run('/templates/login');
	}

	// Login
	public function dashboardAction () {
		if(!TzAuth::isUserLoggedIn())
			$this->loginAction();
		else
			//More to come
			$this->tzRender->run('/templates/dashboard', TzAuth::readSession());
	}
}