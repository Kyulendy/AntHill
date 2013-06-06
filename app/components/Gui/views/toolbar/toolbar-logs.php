<h2>Fichier logs</h2>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Line</th>
            <th>File</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
	    <?php foreach($logs AS $key => $value): ?>
	      	<tr>  
	        <?php if (is_array($value)) : ?>
	        	<td style="width: 200px"><?php print $value['date']; ?></td>
	        	<td><?php print $value['type']; ?></td>
	        	<td><?php print $value['line']; ?></td>
	        	<td><?php print $value['file']; ?></td>
	        	<td><?php print $value['message']; ?></td>
	        <?php endif; ?>
	        </tr>
	    <?php endforeach; ?>
    </tbody>
</table>
