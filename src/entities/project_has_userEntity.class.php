<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class project_has_userEntity {
					
			private $id_project;
			
			private $id_user;
			
			private $id_role;
			
			private $date_added;
			
            private $relations = array('project'=>array('id_project'=>'id_project'),'users'=>array('id_user'=>'id_user'),'role'=>array('id_role'=>'id_role'),);
        
            private $project;
            
            private $users;
            
            private $role;
            



			/********************** GETTER ***********************/
			

			public function getId_project(){
				return $this->id_project;
			}

			

			public function getId_user(){
				return $this->id_user;
			}

			

			public function getId_role(){
				return $this->id_role;
			}

			

			public function getDate_added(){
				return $this->date_added;
			}

			
			/********************** SETTER ***********************/

			public function setId_project($val){
				$this->id_project =  $val;
			}

					

			public function setId_user($val){
				$this->id_user =  $val;
			}

					

			public function setId_role($val){
				$this->id_role =  $val;
			}

					

			public function setDate_added($val){
				$this->date_added =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_role)){

					$sql = "DELETE FROM project_has_user WHERE id_role = ".intval($this->id_role).";";

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

				$sql = 'UPDATE `project_has_user` SET `id_project` = "'.$this->id_project.'", `id_user` = "'.$this->id_user.'", `id_role` = "'.$this->id_role.'", `date_added` = "'.$this->date_added.'" WHERE id_role = '.intval($this->id_role);

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if(!empty($this->id_role)){
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

				$this->id_role = '';

				$sql = 'INSERT INTO project_has_user (`id_project`,`id_user`,`id_role`,`date_added`) VALUES ("'.$this->id_project.'","'.$this->id_user.'","'.$this->id_role.'","'.$this->date_added.'")';

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if($result){
					$lastid = TzSQL::getPDO()->lastInsertId();
					$this->id_role = $lastid;
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** FindAll ***********************/
			public function findAll($recursif = 'yes'){

				$sql = 'SELECT * FROM project_has_user';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new project_has_userEntity();

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
					
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'id_role':
						$param = 'id_role';
						break;
						
					case $param == 'date_added':
						$param = 'date_added';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM project_has_user WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_project = $result->id_project;
					
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$result->id_project, 'no');
                    $this->project = $contentId_project;
                $this->id_user = $result->id_user;
					
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$result->id_user, 'no');
                    $this->users = $contentId_user;
                $this->id_role = $result->id_role;
					
                    $entityId_role = tzSQL::getEntity('role');
                    $contentId_role =  $entityId_role->findManyBy('id_role',$result->id_role, 'no');
                    $this->role = $contentId_role;
                $this->date_added = $result->date_added;
					
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM project_has_user WHERE id_role = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_project = $formatResult->id_project;
				
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$formatResult->id_project, 'no');
                    $this->project = $contentId_project;
                	$this->id_user = $formatResult->id_user;
				
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$formatResult->id_user, 'no');
                    $this->users = $contentId_user;
                	$this->id_role = $formatResult->id_role;
				
                    $entityId_role = tzSQL::getEntity('role');
                    $contentId_role =  $entityId_role->findManyBy('id_role',$formatResult->id_role, 'no');
                    $this->role = $contentId_role;
                	$this->date_added = $formatResult->date_added;
				
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
					
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'id_role':
						$param = 'id_role';
						break;
						
					case $param == 'date_added':
						$param = 'date_added';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM project_has_user WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new project_has_userEntity();

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
					