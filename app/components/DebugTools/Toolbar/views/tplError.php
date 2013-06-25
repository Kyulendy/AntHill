<?php 
	use Components\DebugTools\DebugTool;
 ?>

<div id="currentError" style="display:block;">
	<div class="modal-body">
		<?php 
			foreach (DebugTool::$errorExtend->getTemplateHTMLError() as $value) {
				print $value;
			}
		?>
	</div>
</div>