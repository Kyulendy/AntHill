
	<?php

				

		class actionEntity {
					
			private $id_action;
			
			private $id_ticket;
			
			private $id_user;
			
			private $type;
			
			private $date_action;
			


			/********************** GETTER ***********************/
			

			public function getId_action(){
				return $this->id_action;
			}

			

			public function getId_ticket(){
				return $this->id_ticket;
			}

			

			public function getId_user(){
				return $this->id_user;
			}

			

			public function getType(){
				return $this->type;
			}

			

			public function getDate_action(){
				return $this->date_action;
			}

			
			/********************** SETTER ***********************/

			public function setId_action($val){
				$this->id_action =  $val;
			}

					

			public function setId_ticket($val){
				$this->id_ticket =  $val;
			}

					

			public function setId_user($val){
				$this->id_user =  $val;
			}

					

			public function setType($val){
				$this->type =  $val;
			}

					

			public function setDate_action($val){
				$this->date_action =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_user)){

					$sql = "DELETE FROM action WHERE id_user = ".intval($this->id_user).";";

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

				$sql = 'UPDATE `action` SET `id_action` = "'.$this->id_action.'", `id_ticket` = "'.$this->id_ticket.'", `id_user` = "'.$this->id_user.'", `type` = "'.$this->type.'", `date_action` = "'.$this->date_action.'" WHERE id_user = '.intval($this->id_user);

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

				$sql = 'INSERT INTO action (`id_action`,`id_ticket`,`id_user`,`type`,`date_action`) VALUES ("'.$this->id_action.'","'.$this->id_ticket.'","'.$this->id_user.'","'.$this->type.'","'.$this->date_action.'")';

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
			public function findAll(){

				$sql = 'SELECT * FROM action';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new actionEntity();

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
					
					case $param == 'id_action':
						$param = 'id_action';
						break;
						
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'type':
						$param = 'type';
						break;
						
					case $param == 'date_action':
						$param = 'date_action';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM action WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_action = $result->id_action;
					$this->id_ticket = $result->id_ticket;
					$this->id_user = $result->id_user;
					$this->type = $result->type;
					$this->date_action = $result->date_action;
					
					return true;
				}
				else{
					DebugTool::$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					

			/********************** Find(id) ***********************/
			public function find($id){

				$sql = 'SELECT * FROM action WHERE id_user = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_action = $formatResult->id_action;
					$this->id_ticket = $formatResult->id_ticket;
					$this->id_user = $formatResult->id_user;
					$this->type = $formatResult->type;
					$this->date_action = $formatResult->date_action;
				
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
					
					case $param == 'id_action':
						$param = 'id_action';
						break;
						
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'type':
						$param = 'type';
						break;
						
					case $param == 'date_action':
						$param = 'date_action';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM action WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new actionEntity();

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
					