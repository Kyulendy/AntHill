
	<?php

				

		class roleEntity {
					
			private $id_role;
			
			private $id_project;
			
			private $name;
			


			/********************** GETTER ***********************/
			

			public function getId_role(){
				return $this->id_role;
			}

			

			public function getId_project(){
				return $this->id_project;
			}

			

			public function getName(){
				return $this->name;
			}

			
			/********************** SETTER ***********************/

			public function setId_role($val){
				$this->id_role =  $val;
			}

					

			public function setId_project($val){
				$this->id_project =  $val;
			}

					

			public function setName($val){
				$this->name =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_project)){

					$sql = "DELETE FROM role WHERE id_project = ".intval($this->id_project).";";

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

				$sql = 'UPDATE `role` SET `id_role` = "'.$this->id_role.'", `id_project` = "'.$this->id_project.'", `name` = "'.$this->name.'" WHERE id_project = '.intval($this->id_project);

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if(!empty($this->id_project)){
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

				$this->id_project = '';

				$sql = 'INSERT INTO role (`id_role`,`id_project`,`name`) VALUES ("'.$this->id_role.'","'.$this->id_project.'","'.$this->name.'")';

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if($result){
					$lastid = TzSQL::getPDO()->lastInsertId();
					$this->id_project = $lastid;
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** FindAll ***********************/
			public function findAll(){

				$sql = 'SELECT * FROM role';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new roleEntity();

					foreach ($data as $k => $value) {

						$method = 'set'.ucfirst($k);
						$tmpInstance->$method($value);
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
					
					case $param == 'id_role':
						$param = 'id_role';
						break;
						
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'name':
						$param = 'name';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM role WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_role = $result->id_role;
					$this->id_project = $result->id_project;
					$this->name = $result->name;
					
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM role WHERE id_project = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_role = $formatResult->id_role;
					$this->id_project = $formatResult->id_project;
					$this->name = $formatResult->name;
				
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}
			

			/************* FindManyBy(column, value) ***************/
			public function findManyBy($param,$value){


				switch ($param){
					
					case $param == 'id_role':
						$param = 'id_role';
						break;
						
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'name':
						$param = 'name';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM role WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new roleEntity();

						foreach ($data as $k => $value) {

							$method = 'set'.ucfirst($k);
							$tmpInstance->$method($value);
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
					