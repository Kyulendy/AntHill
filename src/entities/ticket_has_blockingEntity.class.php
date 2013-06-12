
	<?php

				

		class ticket_has_blockingEntity {
					
			private $id_ticket;
			
			private $id_blocking;
			


			/********************** GETTER ***********************/
			

			public function getId_ticket(){
				return $this->id_ticket;
			}

			

			public function getId_blocking(){
				return $this->id_blocking;
			}

			
			/********************** SETTER ***********************/

			public function setId_ticket($val){
				$this->id_ticket =  $val;
			}

					

			public function setId_blocking($val){
				$this->id_blocking =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_blocking)){

					$sql = "DELETE FROM ticket_has_blocking WHERE id_blocking = ".intval($this->id_blocking).";";

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

				$sql = 'UPDATE `ticket_has_blocking` SET `id_ticket` = "'.$this->id_ticket.'", `id_blocking` = "'.$this->id_blocking.'" WHERE id_blocking = '.intval($this->id_blocking);

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if(!empty($this->id_blocking)){
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

				$this->id_blocking = '';

				$sql = 'INSERT INTO ticket_has_blocking (`id_ticket`,`id_blocking`) VALUES ("'.$this->id_ticket.'","'.$this->id_blocking.'")';

				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();

				if($result){
					$lastid = TzSQL::getPDO()->lastInsertId();
					$this->id_blocking = $lastid;
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					

			/********************** FindAll ***********************/
			public function findAll(){

				$sql = 'SELECT * FROM ticket_has_blocking';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new ticket_has_blockingEntity();

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
					
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_blocking':
						$param = 'id_blocking';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket_has_blocking WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_ticket = $result->id_ticket;
					$this->id_blocking = $result->id_blocking;
					
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM ticket_has_blocking WHERE id_blocking = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_ticket = $formatResult->id_ticket;
					$this->id_blocking = $formatResult->id_blocking;
				
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
					
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_blocking':
						$param = 'id_blocking';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket_has_blocking WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new ticket_has_blockingEntity();

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
					