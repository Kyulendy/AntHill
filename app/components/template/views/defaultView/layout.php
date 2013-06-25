<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<meta name="description" content="" />
		<meta name="author" content="" />
				
		<title></title>

		<style type="text/css">

	  		body {
	  			padding:0;
	  			margin:0;
	  			font-size: 14px;
	  			padding-top:40px;
	  			line-height: 20px;
	  			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
	  		}

	  		article, aside, details, figcaption, figure, footer, header, hgroup, nav, section {
    			display: block;
			}

			a {
				color:#0088cc;
				text-decoration: none;
			}

			a:hover {
				color:#005580;
				text-decoration: none;
			}

			section {
				padding-top:30px;
			}

			section header {
				border-bottom: 1px solid #EEEEEE;
			    margin: 20px 0 30px;
			    padding-bottom: 9px;
			    color: #5A5A5A;
			}

			section header h1 {
				font-size: 38.5px;
				line-height: 40px;
				margin:10px 0;
			}

			img {
				border: 0 none;
				height:auto;
				vertical-align: middle;
				max-width: 100%;
			}

  			#nav-top {
  				top:0;
  				width:100%;
  				z-index:1030;
  				overflow:visible;
  				font-size: 13px;
  				position:fixed;
  				height:40px;
  				background-color: #1B1B1B;
  				background-image: linear-gradient(to bottom, #222222, #111111);
  				background-repeat: repeat-x;
  				border-color:#252525;
  				-webkit-box-shadow:0 1px 10px rgba(0, 0, 0, 0.1);
			   -moz-box-shadow:0 1px 10px rgba(0, 0, 0, 0.1);
			        box-shadow:0 1px 10px rgba(0, 0, 0, 0.1);
  			}

  			.btn {
  				display:inline-block;
  				border: 0 none;
			    border-radius: 6px 6px 6px 6px;
			    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1) inset, 0 1px 5px rgba(0, 0, 0, 0.25);
			    color: #FFFFFF;
			    font-size: 24px;
			    font-weight: 200;
			    padding: 19px 24px;
			    transition: none 0s ease 0s;

			    background: #62c462; /* Old browsers */
				background: -moz-linear-gradient(top,  #62c462 0%, #51a351 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#62c462), color-stop(100%,#51a351)); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(top,  #62c462 0%,#51a351 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(top,  #62c462 0%,#51a351 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(top,  #62c462 0%,#51a351 100%); /* IE10+ */
				background: linear-gradient(to bottom,  #62c462 0%,#51a351 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#62c462', endColorstr='#51a351',GradientType=0 ); /* IE6-9 */

			    background-repeat: repeat-x;
			    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
			    color: #FFFFFF;
			    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
  			}

  			.btn:hover {
  				color: #FFFFFF;
  				box-shadow:0 1px 0 rgba(255, 255, 255, 0.1) inset, 0 1px 5px rgba(0, 0, 0 , 0.25);
  				text-shadow: 0 0 10px rgba(255, 255, 255, 0.25);
  			}

  			.container {
  				width: 1170px;
  				margin: auto;
  				min-height: 40px;
  			}

  			#documentation_link {
  				float:right;
  			}

  			#success {
  				text-align: left;
  				position: relative;
  				color: #FFFFFF;
  				text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4), 0 0 30px rgba(0, 0, 0, 0.075);
  				background: #34a5ec; /* Old browsers */
				background: -moz-linear-gradient(left,  #34a5ec 0%, #12d96b 100%); /* FF3.6+ */
				background: -webkit-gradient(linear, left top, right top, color-stop(0%,#34a5ec), color-stop(100%,#12d96b)); /* Chrome,Safari4+ */
				background: -webkit-linear-gradient(left,  #34a5ec 0%,#12d96b 100%); /* Chrome10+,Safari5.1+ */
				background: -o-linear-gradient(left,  #34a5ec 0%,#12d96b 100%); /* Opera 11.10+ */
				background: -ms-linear-gradient(left,  #34a5ec 0%,#12d96b 100%); /* IE10+ */
				background: linear-gradient(to right,  #34a5ec 0%,#12d96b 100%); /* W3C */
				filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#34a5ec', endColorstr='#12d96b',GradientType=1 ); /* IE6-9 */
				-webkit-box-shadow:0 3px 7px rgba(0, 0, 0, 0.2) inset, 0 -3px 7px rgba(0, 0, 0, 0.2) inset;
				   -moz-box-shadow:0 3px 7px rgba(0, 0, 0, 0.2) inset, 0 -3px 7px rgba(0, 0, 0, 0.2) inset;
				        box-shadow:0 3px 7px rgba(0, 0, 0, 0.2) inset, 0 -3px 7px rgba(0, 0, 0, 0.2) inset;
				height: auto;
				width:100%;
				padding: 40px 0;
				text-rendering: optimizelegibility;
  			}

  			h1 {
  				font-size: 60px;
  				letter-spacing: -1px;
  				
  			}

  			#success p {
  				font-size: 24px;
  				font-weight: 300px;
  				line-height: 1.25;
  			}

  			.footer {
  				background-color: #F5F5F5;
  				border-top: 1px solid #E5E5E5;
  				margin-top: 70px;
  				text-align: center;
  				padding: 30px 0;
  			}

  			.footer p {
  				color: #777777;
  				margin: 0;
  			}

  		</style>
	</head>
<body>

<?php 
/**
 * C'est ici que vous pouvez inclure les differents tpl
 * qui sont appele dans vos fichier de routing
 *
 *if(TzRender::getPage() == 'Default') {
 *	include_once  ROOT.'/src/views/templates/default.php'; 
 *} elseif (TzRender::getPage() == 'Article') {
 *	include_once  ROOT.'/src/views/templates/article.php'; 
 *}
 */

if(Components\RenderTplEngine\TzRender::getPage() == 'Default') {
 	include_once  ROOT.'/src/views/templates/default.php'; 
}

?>
	<footer class="footer">
		<div class="container">
			<p>
				Designed and built by
				<img src="<?php echo $WEB_PATH."/tiitz/img/france.png"; ?>" />
				<a href="http://fr.linkedin.com/in/arnaudraulet/" target="_blank">
					Epok
				</a>,
				<img src="<?php echo $WEB_PATH."/tiitz/img/france.png"; ?>" />
				<a href="http://fr.linkedin.com/in/benjamindebernardi/" target="_blank">
					GeneSoR
				</a>,
				<img src="<?php echo $WEB_PATH."/tiitz/img/germany.png"; ?>" />
				<a href="http://www.linkedin.com/pub/cl%C3%A9ment-seiller/65/36a/a56" target="_blank">
					Seiller
				</a>,
				<img src="<?php echo $WEB_PATH."/tiitz/img/france.png"; ?>" />
				<a href="http://fr.linkedin.com/in/romainreynaud" target="_blank">
					Radyum
				</a>,
				<img src="<?php echo $WEB_PATH."/tiitz/img/france.png"; ?>" />
				<a href="http://fr.linkedin.com/pub/cyril-teixeira/58/827/89a" target="_blank">
					Ekito
				</a>,
				<img src="<?php echo $WEB_PATH."/tiitz/img/france.png"; ?>" />
				<a href="http://fr.linkedin.com/pub/guillaume-tellier/48/208/b57" target="_blank">
					Zra
				</a>,
			</p>
			<p>Code licensed under MIT license</p>
			<br />
			<br />
			<p>
				<img src="<?php echo $WEB_PATH."/tiitz/img/logoSup.png"; ?>" width="90px" />
			</p>
		</div>
	</footer>
</body>
</html>