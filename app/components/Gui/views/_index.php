<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>TiiTz Framework</title>
	
	<link href='<?php print WEB_PATH;?>/tiitz/css/bootstrap.css' rel='stylesheet' type='text/css' />
	<link href='<?php print WEB_PATH;?>/tiitz/css/style-gui.css' rel='stylesheet' type='text/css' />

	<script type="text/javascript" src="<?php print WEB_PATH;?>/tiitz/js/jquery-1.9.0.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
</head>					 
<body>
<div class="tiitz">	
	<div class="container" style="background-color : #eee;margin: 20px auto 80px;padding:10px;width:460px;">
		<div class="row">
			<div class="span6">
			  	<h3 style="margin: auto;text-align: center;">Tiitz Framework</h3>
				<form method="post" action="<?php print $_SERVER["SCRIPT_NAME"] ?>">
				

				<div class="">
					<h4>Moteur de templates</h4>

					<label for="tpl">Choix : </label>
					<select id="tpl" name="tpl" class="input-block-level">
						<option value="twig">Twig</option>
						<option value="smarty">Smarty</option>
						<option value="php">Aucun</option>
					</select>
				</div>
				<?php if(isset($error['tpl'])) : ?>
					<div class="error-tiitz">
						<p class="tiitz-error"><?php print $error['tpl']; ?></p>
					</div>
				<?php endif; ?>
				
				
				<div class="">
					<h4>Routing</h4>
					<div>
						<label for="routesLang">Langage : </label>
						<select id="routesLang" name="routesLang" class="input-block-level">
							<option value="yml">YAML</option>
							<option value="php">PHP</option>
						</select>
					</div>
					<?php if(isset($error['route'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['route']; ?></p>
						</div>
					<?php endif; ?>
				</div>	


				<div class="">
					
					<h4>Base de données</h4>
					<?php if(isset($error['connectDb'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['connectDb']; ?></p>
						</div>
					<?php endif; ?>
					<div>
						<label for="user">Utilisateur : </label>
						<input type="text" name="user" <?php if(isset($error['user_value'])) print "value='".$error['user_value']."'"; ?> id="user" placeholder="root" class="input-block-level" />
					</div>
					<?php if(isset($error['user'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['user']; ?></p>
						</div>
					<?php endif; ?>
					<div>
						<label for="pwd">Mot de Passe : </label>
						<input type="password" name="pwd" id="pwd" class="input-block-level"/>
					</div>
					<?php if(isset($error['pwd'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['pwd']; ?></p>
						</div>
					<?php endif; ?>
					<div>
						<label for="adress">Hôte : </label>
						<input type="text" name="adress" <?php if(isset($error['adress_value'])) print "value='".$error['adress_value']."'"; ?> id="adress" placeholder="127.0.0.1" class="input-block-level"/>
					</div>
					<?php if(isset($error['adress'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['adress']; ?></p>
						</div>
					<?php endif; ?>
					<div>		
						<label for="name">Nom : </label>
						<input type="text" name="name" <?php if(isset($error['name_value'])) print "value='".$error['name_value']."'"; ?> id="name" placeholder="tiitzBDD" class="input-block-level" />
					</div>
					<?php if(isset($error['name'])) : ?>
						<div class="error-tiitz">
							<p class="tiitz-error"><?php print $error['name']; ?></p>
						</div>
					<?php endif; ?>
				</div>

				<div>
					<h4>Nouvelles pages</h4>
					<div>
						<label for="">Nom</label>
						<input class='field input-block-level' id='firstField' type='text' name='pages[]' /><br id='REPERE' />
						<a class='btn btn-success' href='javascript:void(0)' onclick='addField()'><i class='icon-plus icon-white'></i></a>
					</div>	
							
				</div>

				<div class="formEnd">
					<input type="submit" value="Terminer" name="firstConfig" class="btn btn-large btn-primary" />
				</div>

				</form>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript" src="tiitz/js/gui.js"></script>
</body>
</html>