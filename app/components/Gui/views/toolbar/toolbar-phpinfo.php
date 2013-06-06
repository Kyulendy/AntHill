<?php if(PATH_TOOLBAR == 'phpinfo-configuration') { ?>
<h2>Configuration phpinfo()</h2>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Directives</th>
            <th>Valeurs</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($config AS $key => $value): ?>
        <tr>
            <td><?php print $key ?></td>
            <td><?php
                if(!is_array($value)) print $value;
                else {
                    foreach($value AS $result){
                        print $result.'<br />';
                    }
                }
            ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php } elseif(PATH_TOOLBAR == 'phpinfo-module') { ?>
    <?php foreach($module AS $name => $config): ?>
        <h2><?php print $name ?></h2>
        <table class="table table-hover">

            <thead>
            <tr>
                <th>Directives</th>
                <th>Valeurs</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($config AS $key => $value): ?>
                <tr>
                    <td><?php print $key ?></td>
                    <td><?php
                        if(!is_array($value)) print $value;
                        else {
                            foreach($value AS $result){
                                print $result.'<br />';
                            }
                        }
                        ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
<?php
    endforeach;
} ?>