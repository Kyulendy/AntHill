<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />

		<meta name="description" content="" />
		<meta name="author" content="" />
				
		<title></title>
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
<p>Ceci est un footer</p>
</body>
</html>