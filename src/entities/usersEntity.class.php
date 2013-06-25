<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class usersEntity {
					
			private $id_user;
			
			private $email;
			
			private $first_name;
			
			private $last_name;
			
			private $password;
			
			private $status;
			
            private $relations = array();
        



			/********************** GETTER ***********************/
			

			public function getId_user(){
				return $this->id_user;
			}

			

			public function getEmail(){
				return $this->email;
			}

			

			public function getFirst_name(){
				return $this->first_name;
			}

			

			public function getLast_name(){
				return $this->last_name;
			}

			

			public function getPassword(){
				return $this->password;
			}

			

			public function getStatus(){
				return $this->status;
			}

			
			/********************** SETTER ***********************/

			public function setId_user($val){
				$this->id_user =  $val;
			}

					

			public function setEmail($val){
				$this->email =  $val;
			}

					

			public function setFirst_name($val){
				$this->first_name =  $val;
			}

					

			public function setLast_name($val){
				$this->last_name =  $val;
			}

					

			public function setPassword($val){
				$this->password =  $val;
			}

					

			public function setStatus($val){
				$this->status =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_user)){

					$sql = "DELETE FROM users WHERE id_user = ".intval($this->id_user).";";

					$result = TzSQL::getPDO()->prepare($sql);
					$result->execute();

					return $result;
				}
				else{
					DebugTool::$error->catchError(array('Fail delete', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** Update ***********************/

			public function Update(){

				$sql = 'UPDATE `users` SET `id_user` = "'.$this->id_user.'", `email` = "'.$this->email.'", `first_name` = "'.$this->first_name.'", `last_name` = "'.$this->last_name.'", `password` = "'.$this->password.'", `status` = "'.$this->status.'" WHERE id_user = '.intval($this->id_user);

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if(!empty($this->id_user)){
					if($result)
						return true;
					else{
						DebugTool::$error->catchError(array('Fail update', __FILE__,__LINE__, true));
						return false;
					}
				}
				else{
					DebugTool::$error->catchError(array('Fail update: primkey is null', __FILE__,__LINE__, true));
					return false;
				}
			}

			/********************** Insert ***********************/

			public function Insert(){

				$this->id_user = '';

				$sql = 'INSERT INTO users (`id_user`,`email`,`first_name`,`last_name`,`password`,`status`) VALUES ("'.$this->id_user.'","'.$this->email.'","'.$this->first_name.'","'.$this->last_name.'","'.$this->password.'","'.$this->status.'")';

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if($result){
					$lastid = TzSQL::getPDO()->lastInsertId();
					$this->id_user = $lastid;
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** FindAll ***********************/
			public function findAll($recursif = 'yes'){

				$sql = 'SELECT * FROM users';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new usersEntity();

					foreach ($data as $k => $value) {

						$method = 'set'.ucfirst($k);
						$tmpInstance->$method($value);

						if($recursif == null){
                            foreach($this->relations as $relationId => $relationLinks){
                                if(array_key_exists($k, $relationLinks)){
                                    $entity = tzSQL::getEntity($relationId);
                                    $content =  $entity->findManyBy($relationLinks[$k],$value, 'no');
                                    $tmpInstance->$relationId = $content;
                                }
                            }
                        }
					}
					array_push($entitiesArray, $tmpInstance);
				}

				if(!empty($entitiesArray))
					return $entitiesArray;
				else{
					DebugTool::$error->catchError(array('No results', __FILE__,__LINE__, true));
					return false;
				}						

			}

			/************* FindOneBy(column, value) ***************/
			public function findOneBy($param,$value){


				switch ($param){
					
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'email':
						$param = 'email';
						break;
						
					case $param == 'first_name':
						$param = 'first_name';
						break;
						
					case $param == 'last_name':
						$param = 'last_name';
						break;
						
					case $param == 'password':
						$param = 'password';
						break;
						
					case $param == 'status':
						$param = 'status';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM users WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_user = $result->id_user;
					$this->email = $result->email;
					$this->first_name = $result->first_name;
					$this->last_name = $result->last_name;
					$this->password = $result->password;
					$this->status = $result->status;
					
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM users WHERE id_user = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_user = $formatResult->id_user;
					$this->email = $formatResult->email;
					$this->first_name = $formatResult->first_name;
					$this->last_name = $formatResult->last_name;
					$this->password = $formatResult->password;
					$this->status = $formatResult->status;
				
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}
			

			/************* FindManyBy(column, value) ***************/
			public function findManyBy($param,$value,$recursif = 'yes'){


				switch ($param){
					
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'email':
						$param = 'email';
						break;
						
					case $param == 'first_name':
						$param = 'first_name';
						break;
						
					case $param == 'last_name':
						$param = 'last_name';
						break;
						
					case $param == 'password':
						$param = 'password';
						break;
						
					case $param == 'status':
						$param = 'status';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM users WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new usersEntity();

						foreach ($data as $k => $value) {

							$method = 'set'.ucfirst($k);
							$tmpInstance->$method($value);

                            if($recursif == 'yes'){
                                foreach($this->relations as $relationId => $relationLinks){
                                    if(array_key_exists($k, $relationLinks)){
                                        $entity = tzSQL::getEntity($relationId);
                                        $content =  $entity->findManyBy($relationLinks[$k],$value, 'no');
                                        $tmpInstance->$relationId = $content;
                                    }
                                }
                            }

						}
						array_push($entitiesArray, $tmpInstance);
					}

					if($entitiesArray)
						return $entitiesArray;
					else{
						DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
						return false;
					}

				}
			}

					

		}

	?>
					