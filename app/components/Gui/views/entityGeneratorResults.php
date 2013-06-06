<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
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
		<div class="container">
			<div class="hero-unit">
		        <h2>Generateur d'entités</h2>
		        <br>
		        <p>Les entités suivantes ont été traitées</p>
				<table class="table table-hover" style="text-align:center;">
					<?php 
						if(isset($results)){
							foreach ($results as $key => $value) {
								if($value == true)
									echo "
									<tr class='success'>
										<td><i class='icon-check'></i> </td>
										<td>".$key."</td>
									</tr>";
								else
									echo "
									<tr class='error'>
										<td><i class='icon-remove'></i> ".$key."</td>
									</tr>";
							}
						}
					?>
				</table>
				<div class="row-fluid" style='text-align:center'>
					<a class="btn btn-primary btn-large" type="submit" name="generateEntity" href="<?php print WEB_PATH; ?>">Revenir à la page d'acceuil</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>