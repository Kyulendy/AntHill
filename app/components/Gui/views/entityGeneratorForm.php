<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>TiiTz Framework</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link href='<?php echo WEB_PATH;?>/tiitz/css/bootstrap.css' rel='stylesheet' type='text/css' />
	<style type="text/css">
		* {
			font-family: "Helvetica Neue";
		}
	</style>
</head>					 
<body>
	<div class="tiitz">
		<div class="container-fluid" style="width:80%; margin:auto;">
			<div class="hero-unit">
		        <h2>Generateur d'entités</h2>
		        <p>
		        	Pour générer vos entités: 
		        	<ul>
		        		<li>
		        			Verifiez bien vos nom de colonnes sur chaques table ainsi que la présence d'une "PRIMARY KEY" (ici "PRI")
		        		</li>
		        		<li>
		        			Selectionnez les tables dont vous voulez générer les entités, puis cliquez simplement sur "Générer"
		        		</li> 
		        	</ul>
		        	Vous pourrez ainsi accéder ces entités dans votre projet et faire appel aux fonctions liées à cette entité, pour plus de précisions sur les entités référez vous à la documentation.
		        </p>
		        <br>
		        <form action="<?php echo WEB_PATH; ?>/configTiitz/entityGenerator" method="POST" name="formgeneration">
					<?php
						$i = 0;
						foreach ($tables as $key => $value) {

							if($i == 0){
								echo "<div class='row-fluid'>";
							}

							echo '<div class="span4" style="height:200px; margin:20px 0 20px 0; overflow-y:auto;">';

							echo '<table class="table table-hover">
									<thead>
										<tr>
											<th>
												<input type="checkbox" checked="checked" name="tablename[]" value="'.$value[0].'"> '.$value[0].'
											</th>
										</tr>
									</thead>';

							echo "
									<tbody>";

							foreach ($columsList[$value[0]] as $key2 => $value2) {
								echo "<tr>";
								if($value2['Key'] == 'PRI'){
									echo "<td>".$value2['Field'].' - PRI</td>';
									echo '<td><input type="hidden"  name="'.$value[0].'primKey" value="'.$value2['Field'].'"></td>';
				 
								} else{
									echo "<td>".$value2['Field'].'</td>';
								}
								echo "</tr>";
								
							}

							echo "
									</tbody>
								</table>
							</div>";

							$i++;

							if($i == 3){
								echo "</div>";
								$i=0;
							}
						}
						if((count($tables) % 3) != 0){
							echo "</div>";
						}
					?>
					<br>
					<div class="row-fluid" style='text-align:center'>
						<input class=" btn btn-primary btn-large" type="submit" name="generateEntity" value="Générer">
					</div>
				</form>
    		</div>
		</div>
	</div>
</body>
</html>