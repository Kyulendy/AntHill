<h2>Fichier config.yml</h2>
<?php
$config = Components\Kernel\tzKernel::$tzConf;
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
