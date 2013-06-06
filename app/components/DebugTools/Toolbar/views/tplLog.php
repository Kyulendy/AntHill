<?php
  $i = 0;
?>
<div id="myLogError" style="display:block;font-size: 13px!important;">
	<div class="modal-body">
		<?php foreach (DebugTool::$errorExtend->getArrayOfError() AS $key => $value): ?>
      <?php foreach ($value as $errorLog) : ?>
         <div <?php ($i%2 == 0) ? print "class='odd'" : print "class='even'" ?>>
          <p><strong>Date </strong>: <?php print $value['date']; ?> | <strong>Num&eacute;ro erreur </strong>: <?php print $value['type']; ?></p>
          <p><strong>Message </strong>: <?php print $value['message']; ?></p>
          <p><strong>File </strong>: <?php print $value['file']; ?> | <strong>Ligne </strong>: <?php print $value['line']; ?></p>
        </div>  
        <?php $i++; ?>
      <?php endforeach ?>	
    <?php endforeach ?>
    <?php if ($i == 0) : ?>
      <p>Le fichier de log est vide</p>
    <?php endif; ?> 
	</div>
</div>