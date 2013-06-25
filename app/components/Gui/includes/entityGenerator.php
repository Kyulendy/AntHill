<?php

use Components\SQLEntities\TzSQL;

function createEntity($tables){

	$results = array();

	foreach ($tables as $tablename) {

		// fill parameters from form
		$table = $tablename;
		$class = $tablename."Entity";

		$primkey = empty($_POST[$tablename.'primKey']) ? false : $_POST[$tablename.'primKey'] ;

		$sql = "SHOW COLUMNS FROM ".$table;
		$colums = TzSQL::getPDO()->prepare( $sql );
		$colums->execute();
		$columsResult = $colums->fetchAll();

		$filename = ROOT . "/src/entities/" . $class . ".class.php";


		// open file in insert mode
		$file = fopen( $filename, "w+" );
		$filedate = date( "d.m.Y" );

        $dbNameQuery = TzSQL::getPDO()->prepare("SELECT DATABASE() as dbname");
        $dbNameQuery->execute();
        $dbName = $dbNameQuery->fetchAll(\PDO::FETCH_ASSOC);

        $foreignKeyQuery ="SELECT A.TABLE_SCHEMA AS FKTABLE_SCHEM, A.TABLE_NAME AS FKTABLE_NAME, A.COLUMN_NAME AS FKCOLUMN_NAME,
                                A.REFERENCED_TABLE_SCHEMA AS PKTABLE_SCHEM, A.REFERENCED_TABLE_NAME AS PKTABLE_NAME,
                                A.REFERENCED_COLUMN_NAME AS PKCOLUMN_NAME
                                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE A, INFORMATION_SCHEMA.TABLE_CONSTRAINTS B
                                WHERE A.TABLE_SCHEMA = B.TABLE_SCHEMA AND A.TABLE_NAME = B.TABLE_NAME
                                AND A.CONSTRAINT_NAME = B.CONSTRAINT_NAME AND B.CONSTRAINT_TYPE IS NOT NULL
                                HAVING PKTABLE_SCHEM IS NOT NULL
                                and A.TABLE_SCHEMA = :dbname
                                and A.TABLE_NAME = :tbname
                                ORDER BY A.TABLE_SCHEMA, A.TABLE_NAME, A.ORDINAL_POSITION limit 1000";

        $getFK = TzSQL::getPDO()->prepare($foreignKeyQuery);
        $getFK->execute(array(":dbname"=>$dbName[0]['dbname'],":tbname"=>$tablename));

        $FK = $getFK->fetchAll(\PDO::FETCH_ASSOC);


		$c = "<?php
		use Components\SQLEntities\TzSQL;
		use Components\DebugTools\DebugTool;

		class $class {
					";


		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			$c.= "
			private $$col;
			";
		}

        $c.= "
            private $"."relations = array(";

        foreach($FK as $relation){
            $c.= "'".$relation['PKTABLE_NAME']."'=>array('".$relation['FKCOLUMN_NAME']."'=>'".$relation['PKCOLUMN_NAME']."'),";
        }
        $c.= ");
        ";

        foreach($FK as $relation){
            $c.= "
            private $".$relation['PKTABLE_NAME'].";
            ";
        }

		$c.="



			/********************** GETTER ***********************/
			";

		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			$mname = "get" . ucfirst( $col ) . "()";
			$mthis = "$" . "this->" . $col;
			$c.="

			public function $mname{
				return $mthis;
			}

			";
		}

		$c.= "
			/********************** SETTER ***********************/";

		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			$c.=
				"

			public function set" . ucfirst( $col ) . "($" . "val){
				$" . "this->" . $col . " =  $" . "val;
			}

					";
		}

		$c.="

			/********************** Delete ***********************/

			public function Delete(){

				if(!empty($" . "this->".$primkey.")){

					$" . "sql" . " = \"DELETE FROM $table WHERE ". $primkey ." = \".intval($" . "this->" . $primkey .").\";\";

					$" . "result = TzSQL::get" . "PDO()->prepare($" . "sql);
					$" . "result->execute();

					return $". "result;
				}
				else{
					DebugTool::\$error->catchError(array('Fail delete', __FILE__,__LINE__, true));
					return false;
				}
			}
					";

		$count = 0;

		$sql = "UPDATE `".$table."` SET ";
		foreach ( $columsResult as $key => $value ) {
			$col = $value[0];

			if ( $count < ( count( $columsResult ) -1 ) )
				$sql .= "`".$col."` = \"'.$" . "this->$col.'\", ";
			else
				$sql .= "`".$col."` = \"'.$" . "this->$col.'\" ";

			$count++;
		}

		$sql .= "WHERE ".$primkey." = '.intval($" . "this->".$primkey.")";

		$c.="

			/********************** Update ***********************/

			public function Update(){

				$" . "sql = '".$sql.";

				$" . "result = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "result->execute();

				if(!empty($". "this->" .$primkey. ")){
					if($" . "result)
						return true;
					else{
						DebugTool::\$error->catchError(array('Fail update', __FILE__,__LINE__, true));
						return false;
					}
				}
				else{
					DebugTool::\$error->catchError(array('Fail update: primkey is null', __FILE__,__LINE__, true));
					return false;
				}
			}";

		$count = 0;

		$sql = "INSERT INTO ".$table." (";

		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			if ( $count < ( count( $columsResult ) - 1 ) )
				$sql .= "`".$col."`,";
			else
				$sql .= "`".$col."`)";

			$count++;
		}

		//remise du compteur a 0
		$count = 0;

		$sql .= " VALUES (";

		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			if ( $count < ( count( $columsResult ) - 1 ) )
				$sql .= "\"'.$" . "this->$col.'\",";
			else
				$sql .= "\"'.$" . "this->$col.'\")";

			$count++;
		}

		$c.="

			/********************** Insert ***********************/

			public function Insert(){

				$" . "this->".$primkey." = '';

				$" . "sql = '".$sql."';

				$" . "result = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "result->execute();

				if($" . "result){
					$" . "lastid = TzSQL::get" . "PDO()->lastInsertId();
					$" . "this->".$primkey." = $" . "lastid;
					return true;
				}
				else{
					DebugTool::\$error->catchError(array('Fail insert', __FILE__,__LINE__, true));
					return false;
				}
			}
					";


		$c.="

			/********************** FindAll ***********************/
			public function findAll($" . "recursif = 'yes'){

				$" . "sql = 'SELECT * FROM ".$table."';
				$" . "result = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "result->execute();
				$" . "formatResult = $" . "result->fetchAll(PDO::FETCH_ASSOC);
				$" . "entitiesArray = array();

				foreach ($" . "formatResult as $" . "key => $" . "data) {

					$" . "tmpInstance = new ".$class."();

					foreach ($" . "data as $" . "k => $" . "value) {

						$" . "method = 'set'.ucfirst($" . "k);
						$" . "tmpInstance->$" . "method($" . "value);

						if($" . "recursif == null){
                            foreach($" . "this->relations as $" . "relationId => $" . "relationLinks){
                                if(array_key_exists($" . "k, $" . "relationLinks)){
                                    $" . "entity = tzSQL::getEntity($" . "relationId);
                                    $" . "content =  $" . "entity->findManyBy($" . "relationLinks[$" . "k],$" . "value, 'no');
                                    $" . "tmpInstance->$" . "relationId = $" . "content;
                                }
                            }
                        }
					}
					array_push($" . "entitiesArray, $" . "tmpInstance);
				}

				if(!empty($" . "entitiesArray))
					return $" . "entitiesArray;
				else{
					DebugTool::\$error->catchError(array('No results', __FILE__,__LINE__, true));
					return false;
				}						

			}

			/************* FindOneBy(column, value) ***************/
			public function findOneBy($" . "param,$" . "value){


				switch ($" . "param){
					";


		foreach ( $columsResult as $key => $value ) {

			$col = $value[0];

			$c.= "
					case $" . "param == '".$col."':
						$"."param = '".$col."';
						break;
						";
		}

		$c.="
					default:
						DebugTool::\$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$" . "sql =  'SELECT * FROM $table WHERE '.$"."param.' = \"'.$" . "value.'\"';
				$" . "data = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "data->execute();
				$" . "result =  $" . "data->fetch(PDO::FETCH_OBJ);

				if(!empty($" . "result)){
					";

				foreach ( $columsResult as $key => $value ) {

					$col = $value[0];

					$c.="$" . "this->" . $col . " = $" . "result->" . $col.";
					";

                    foreach($FK as $relation){
                        if($relation['FKCOLUMN_NAME'] == $col){
                            $c.="
                    $" . "entity".ucfirst($col)." = tzSQL::getEntity('".$relation['PKTABLE_NAME']."');
                    $" . "content".ucfirst($col)." =  $" . "entity".ucfirst($col)."->findManyBy('".$relation['PKCOLUMN_NAME']."',$" . "result->" . $col.", 'no');
                    $"."this->" . $relation['PKTABLE_NAME'] . " = $" . "content".ucfirst($col).";
                ";
                        }
                    }
				}
		$c.="
					return true;
				}
				else{
					DebugTool::\$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}

					";

		if($primkey){
			$c.=
			"

			/********************** Find(id) ***********************/
			public function find($". "id){

				$" . "sql = 'SELECT * FROM ".$table." WHERE ".$primkey." = ' . $" . "id;
				$" . "result = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "result->execute();
				$" . "formatResult = $" . "result->fetch(PDO::FETCH_OBJ);
				if(!empty($" . "formatResult)){
				";

			foreach ( $columsResult as $key => $value ) {

				$col = $value[0];

				$c.="	$" . "this->" . $col . " = $" . "formatResult->" . $col.";
				";

                foreach($FK as $relation){
                    if($relation['FKCOLUMN_NAME'] == $col){
                        $c.="
                    $" . "entity".ucfirst($col)." = tzSQL::getEntity('".$relation['PKTABLE_NAME']."');
                    $" . "content".ucfirst($col)." =  $" . "entity".ucfirst($col)."->findManyBy('".$relation['PKCOLUMN_NAME']."',$" . "formatResult->" . $col.", 'no');
                    $"."this->" . $relation['PKTABLE_NAME'] . " = $" . "content".ucfirst($col).";
                ";
                    }
                }
            }


			$c.="
					return true;
				}
				else{
					DebugTool::\$error->catchError(array('Result is null', __FILE__,__LINE__, true));
					return false;
				}
			}
			";
		}

		$c.="

			/************* FindManyBy(column, value) ***************/
			public function findManyBy($" . "param,$" . "value,$" . "recursif = 'yes'){


				switch ($" . "param){
					";


		foreach ( $columsResult as $key => $value ) {

			$col = $value[0];

			$c.= "
					case $" . "param == '".$col."':
						$"."param = '".$col."';
						break;
						";
		}


		$c.="
					default:
						DebugTool::\$error->catchError(array('Colonne introuvable: est-elle presente dans la base de donnée ?', __FILE__,__LINE__, true));
						return false;
				}

				$" . "sql =  'SELECT * FROM $table WHERE '.$"."param.' = \"'.$" . "value.'\"';
				$" . "data = TzSQL::get" . "PDO()->prepare($" . "sql);
				$" . "data->execute();
				$" . "formatResult = $" . "data->fetchAll(PDO::FETCH_ASSOC);
				$" . "entitiesArray = array();

				if(!empty($" . "formatResult)){

					foreach ($" . "formatResult as $" . "key => $" . "data) {

						$" . "tmpInstance = new ".$class."();

						foreach ($" . "data as $" . "k => $" . "value) {

							$" . "method = 'set'.ucfirst($" . "k);
							$" . "tmpInstance->$" . "method($" . "value);

                            if($" . "recursif == 'yes'){
                                foreach($" . "this->relations as $" . "relationId => $" . "relationLinks){
                                    if(array_key_exists($" . "k, $" . "relationLinks)){
                                        $" . "entity = tzSQL::getEntity($" . "relationId);
                                        $" . "content =  $" . "entity->findManyBy($" . "relationLinks[$" . "k],$" . "value, 'no');
                                        $" . "tmpInstance->$" . "relationId = $" . "content;
                                    }
                                }
                            }

						}
						array_push($" . "entitiesArray, $" . "tmpInstance);
					}

					if($" . "entitiesArray)
						return $" . "entitiesArray;
					else{
						DebugTool::\$error->catchError(array('Result is null', __FILE__,__LINE__, true));
						return false;
					}

				}
			}

					";

		$c.="

		}

	?>
					";

		if(fwrite( $file, $c ))
		{
			$results[$class] = true;
		}
		else{
			$results[$class] = false;
		}
	}
	return $results;
}