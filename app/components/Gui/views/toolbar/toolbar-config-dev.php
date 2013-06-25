<h2>Fichier config_dev.yml</h2>
<?php
$config = Components\Kernel\tzKernel::$tzDevConf;
?>
<table class="table table-hover">
    <?php
        foreach ($config as $key => $value) {
          if(is_array($value)) {
              foreach ($value as $key => $value) {
                  print '<tr>';
                  print '<th>'.$key.'</th>';
                  print '<td>'.$value.'</td>';
                  print '</tr>';
              }
          } else {
              print '<tr>';
              print '<th>'.$key.'</th>';
              print '<td>'.$value.'</td>';
              print '</tr>';
          }
        }
    ?>
</table>
