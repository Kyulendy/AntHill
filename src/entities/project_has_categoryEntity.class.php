<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class project_has_categoryEntity {
					
			private $id_project;
			
			private $id_category;
			
            private $relations = array('project'=>array('id_project'=>'id_project'),'category'=>array('id_category'=>'id_category'),);
        
            private $project;
            
            private $category;
            



			/********************** GETTER ***********************/
			

			public function getId_project(){
				return $this->id_project;
			}

			

			public function getId_category(){
				return $this->id_category;
			}

			
			/********************** SETTER ***********************/

			public function setId_project($val){
				$this->id_project =  $val;
			}

					

			public function setId_category($val){
				$this->id_category =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_category)){

					$sql = "DELETE FROM project_has_category WHERE id_category = ".intval($this->id_category).";";

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

				$sql = 'UPDATE `project_has_category` SET `id_project` = "'.$this->id_project.'", `id_category` = "'.$this->id_category.'" WHERE id_category = '.intval($this->id_category);

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if(!empty($this->id_category)){
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

				$this->id_category = '';

				$sql = 'INSERT INTO project_has_category (`id_project`,`id_category`) VALUES ("'.$this->id_project.'","'.$this->id_category.'")';

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if($result){
					$lastid = TzSQL::getPDO()->lastInsertId();
					$this->id_category = $lastid;
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** FindAll ***********************/
			public function findAll($recursif = 'yes'){

				$sql = 'SELECT * FROM project_has_category';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new project_has_categoryEntity();

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
						
					case $param == 'id_category':
						$param = 'id_category';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM project_has_category WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_project = $result->id_project;
					
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$result->id_project, 'no');
                    $this->project = $contentId_project;
                $this->id_category = $result->id_category;
					
                    $entityId_category = tzSQL::getEntity('category');
                    $contentId_category =  $entityId_category->findManyBy('id_category',$result->id_category, 'no');
                    $this->category = $contentId_category;
                
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM project_has_category WHERE id_category = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_project = $formatResult->id_project;
				
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$formatResult->id_project, 'no');
                    $this->project = $contentId_project;
                	$this->id_category = $formatResult->id_category;
				
                    $entityId_category = tzSQL::getEntity('category');
                    $contentId_category =  $entityId_category->findManyBy('id_category',$formatResult->id_category, 'no');
                    $this->category = $contentId_category;
                
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
						
					case $param == 'id_category':
						$param = 'id_category';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM project_has_category WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new project_has_categoryEntity();

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
					