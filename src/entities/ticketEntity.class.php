<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class ticketEntity {
					
			private $id_ticket;
			
			private $id_project;
			
			private $id_user;
			
			private $id_category;
			
			private $title;
			
			private $content;
			
			private $date_created;
			
			private $duration_start;
			
			private $duration_type;
			
			private $type;
			
			private $significance;
			
			private $status;
			
            private $relations = array('category'=>array('id_category'=>'id_category'),'users'=>array('id_user'=>'id_user'),'project'=>array('id_project'=>'id_project'),);
        
            private $category;
            
            private $users;
            
            private $project;
            



			/********************** GETTER ***********************/
			

			public function getId_ticket(){
				return $this->id_ticket;
			}

			

			public function getId_project(){
				return $this->id_project;
			}

			

			public function getId_user(){
				return $this->id_user;
			}

			

			public function getId_category(){
				return $this->id_category;
			}

			

			public function getTitle(){
				return $this->title;
			}

			

			public function getContent(){
				return $this->content;
			}

			

			public function getDate_created(){
				return $this->date_created;
			}

			

			public function getDuration_start(){
				return $this->duration_start;
			}

			

			public function getDuration_type(){
				return $this->duration_type;
			}

			

			public function getType(){
				return $this->type;
			}

			

			public function getSignificance(){
				return $this->significance;
			}

			

			public function getStatus(){
				return $this->status;
			}

			
			/********************** SETTER ***********************/

			public function setId_ticket($val){
				$this->id_ticket =  $val;
			}

					

			public function setId_project($val){
				$this->id_project =  $val;
			}

					

			public function setId_user($val){
				$this->id_user =  $val;
			}

					

			public function setId_category($val){
				$this->id_category =  $val;
			}

					

			public function setTitle($val){
				$this->title =  $val;
			}

					

			public function setContent($val){
				$this->content =  $val;
			}

					

			public function setDate_created($val){
				$this->date_created =  $val;
			}

					

			public function setDuration_start($val){
				$this->duration_start =  $val;
			}

					

			public function setDuration_type($val){
				$this->duration_type =  $val;
			}

					

			public function setType($val){
				$this->type =  $val;
			}

					

			public function setSignificance($val){
				$this->significance =  $val;
			}

					

			public function setStatus($val){
				$this->status =  $val;
			}

					

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($this->id_category)){

					$sql = "DELETE FROM ticket WHERE id_category = ".intval($this->id_category).";";

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

				$sql = 'UPDATE `ticket` SET `id_ticket` = "'.$this->id_ticket.'", `id_project` = "'.$this->id_project.'", `id_user` = "'.$this->id_user.'", `id_category` = "'.$this->id_category.'", `title` = "'.$this->title.'", `content` = "'.$this->content.'", `date_created` = "'.$this->date_created.'", `duration_start` = "'.$this->duration_start.'", `duration_type` = "'.$this->duration_type.'", `type` = "'.$this->type.'", `significance` = "'.$this->significance.'", `status` = "'.$this->status.'" WHERE id_category = '.intval($this->id_category);

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

				$sql = 'INSERT INTO ticket (`id_ticket`,`id_project`,`id_user`,`id_category`,`title`,`content`,`date_created`,`duration_start`,`duration_type`,`type`,`significance`,`status`) VALUES ("'.$this->id_ticket.'","'.$this->id_project.'","'.$this->id_user.'","'.$this->id_category.'","'.$this->title.'","'.$this->content.'","'.$this->date_created.'","'.$this->duration_start.'","'.$this->duration_type.'","'.$this->type.'","'.$this->significance.'","'.$this->status.'")';

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

				$sql = 'SELECT * FROM ticket';
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				foreach ($formatResult as $key => $data) {

					$tmpInstance = new ticketEntity();

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
						
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'id_category':
						$param = 'id_category';
						break;
						
					case $param == 'title':
						$param = 'title';
						break;
						
					case $param == 'content':
						$param = 'content';
						break;
						
					case $param == 'date_created':
						$param = 'date_created';
						break;
						
					case $param == 'duration_start':
						$param = 'duration_start';
						break;
						
					case $param == 'duration_type':
						$param = 'duration_type';
						break;
						
					case $param == 'type':
						$param = 'type';
						break;
						
					case $param == 'significance':
						$param = 'significance';
						break;
						
					case $param == 'status':
						$param = 'status';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$result =  $data->fetch(PDO::FETCH_OBJ);

				if(!empty($result)){
					$this->id_ticket = $result->id_ticket;
					$this->id_project = $result->id_project;
					
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$result->id_project, 'no');
                    $this->project = $contentId_project;
                $this->id_user = $result->id_user;
					
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$result->id_user, 'no');
                    $this->users = $contentId_user;
                $this->id_category = $result->id_category;
					
                    $entityId_category = tzSQL::getEntity('category');
                    $contentId_category =  $entityId_category->findManyBy('id_category',$result->id_category, 'no');
                    $this->category = $contentId_category;
                $this->title = $result->title;
					$this->content = $result->content;
					$this->date_created = $result->date_created;
					$this->duration_start = $result->duration_start;
					$this->duration_type = $result->duration_type;
					$this->type = $result->type;
					$this->significance = $result->significance;
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

				$sql = 'SELECT * FROM ticket WHERE id_category = ' . $id;
				$result = TzSQL::getPDO()->prepare($sql);
				$result->execute();
				$formatResult = $result->fetch(PDO::FETCH_OBJ);
				if(!empty($formatResult)){
					$this->id_ticket = $formatResult->id_ticket;
					$this->id_project = $formatResult->id_project;
				
                    $entityId_project = tzSQL::getEntity('project');
                    $contentId_project =  $entityId_project->findManyBy('id_project',$formatResult->id_project, 'no');
                    $this->project = $contentId_project;
                	$this->id_user = $formatResult->id_user;
				
                    $entityId_user = tzSQL::getEntity('users');
                    $contentId_user =  $entityId_user->findManyBy('id_user',$formatResult->id_user, 'no');
                    $this->users = $contentId_user;
                	$this->id_category = $formatResult->id_category;
				
                    $entityId_category = tzSQL::getEntity('category');
                    $contentId_category =  $entityId_category->findManyBy('id_category',$formatResult->id_category, 'no');
                    $this->category = $contentId_category;
                	$this->title = $formatResult->title;
					$this->content = $formatResult->content;
					$this->date_created = $formatResult->date_created;
					$this->duration_start = $formatResult->duration_start;
					$this->duration_type = $formatResult->duration_type;
					$this->type = $formatResult->type;
					$this->significance = $formatResult->significance;
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
					
					case $param == 'id_ticket':
						$param = 'id_ticket';
						break;
						
					case $param == 'id_project':
						$param = 'id_project';
						break;
						
					case $param == 'id_user':
						$param = 'id_user';
						break;
						
					case $param == 'id_category':
						$param = 'id_category';
						break;
						
					case $param == 'title':
						$param = 'title';
						break;
						
					case $param == 'content':
						$param = 'content';
						break;
						
					case $param == 'date_created':
						$param = 'date_created';
						break;
						
					case $param == 'duration_start':
						$param = 'duration_start';
						break;
						
					case $param == 'duration_type':
						$param = 'duration_type';
						break;
						
					case $param == 'type':
						$param = 'type';
						break;
						
					case $param == 'significance':
						$param = 'significance';
						break;
						
					case $param == 'status':
						$param = 'status';
						break;
						
					default:
						DebugTool::$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$sql =  'SELECT * FROM ticket WHERE '.$param.' = "'.$value.'"';
				$data = TzSQL::getPDO()->prepare($sql);
				$data->execute();
				$formatResult = $data->fetchAll(PDO::FETCH_ASSOC);
				$entitiesArray = array();

				if(!empty($formatResult)){

					foreach ($formatResult as $key => $data) {

						$tmpInstance = new ticketEntity();

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
					