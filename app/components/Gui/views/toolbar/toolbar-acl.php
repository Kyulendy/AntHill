<h2>Mise à jour des ACLs</h2>

<div class="clear"><br /></div>

<form method="POST" action="./generate">
    <input type="hidden" name="generation_acl" value="1">
    <input class="btn btn-primary btn-large" type="submit" value="générer les ACL"/>
</form>

<div class="clear"><br /></div>

<?php
if(isset($statusACL)){
    if($statusACL == TRUE){
        echo('Les groupes ont été mis à jour ! Les groupes générés sont : <br /><div class="clear"><br /></div>');
        foreach ($groups as $k=>$g){
            echo($k." : ".$g."<br />");
        }
        
    } 
    else {
        echo('Une erreur est survenue, veuillez vérifier vos informations de base de données.');
    } 
}
?>


