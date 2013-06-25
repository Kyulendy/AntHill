<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class ticket_has_userEntity {
					
			private $id_ticket;
			
			private $id_user;
			
            private $relations = array('users'=>array('id_user'=>'id_user'),'ticket'=>array('id_ticket'=>'id_ticket'),);
        
            private $users;
            
            private $ticket;
            



			/********************** GETTER ***********************/
			

			public function getId_ticket(){
				return $this->id_ticket;
			}

			

			public function getId_user(){
				return $this->id_user;
			}

			
			/********************** SETTER ***********************/

			public function setId_ticket($val){
				$this->id_ticket =  $val;
			}

					

			public function setId_user($val){
				$this->id_user =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_user)){

					$sql = "DELETE FROM ticket_has_user WHERE id_user = ".intval($this->id_user).";";

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

				$sql = 'UPDATE `ticket_has_user` SET `id_ticket` = "'.$this->id_ticket.'", `id_user` = "'.$this->id_user.'" WHERE id_user = '.intval($this->id_user);

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

				$sql = 'INSERT INTO ticket_has_user (`id_ticket`,`id_user`) VALUES ("'.$this->id_ticket.'","'.$this->id_user.'")';

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

				$sql = 'SELECT * FROM ticket_has_user';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new ticket_has_userEntity();

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
					
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket_has_user WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_ticket = $result->id_ticket;
					
                    $entityId_ticket = tzSQL::getEntity('ticket');
                    $contentId_ticket =  $entityId_ticket->findManyBy('id_ticket',$result->id_ticket, 'no');
                    $this->ticket = $contentId_ticket;
                $this->id_user = $result->id_user;
					
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$result->id_user, 'no');
                    $this->users = $contentId_user;
                
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM ticket_has_user WHERE id_user = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_ticket = $formatResult->id_ticket;
				
                    $entityId_ticket = tzSQL::getEntity('ticket');
                    $contentId_ticket =  $entityId_ticket->findManyBy('id_ticket',$formatResult->id_ticket, 'no');
                    $this->ticket = $contentId_ticket;
                	$this->id_user = $formatResult->id_user;
				
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$formatResult->id_user, 'no');
                    $this->users = $contentId_user;
                
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
					
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket_has_user WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new ticket_has_userEntity();

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
					