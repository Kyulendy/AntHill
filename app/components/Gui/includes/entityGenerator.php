<?php

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


		$c = "
	<?php

				

		class $class {
					";


		foreach ( $columsResult as $key => $value ) {
			$col=$value[0];

			$c.= "
			private $$col;
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
			public function findAll(){

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
			public function findManyBy($" . "param,$" . "value){


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