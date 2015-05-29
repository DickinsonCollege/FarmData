<?php
if ($_SESSION['gens']) {
   echo '<div class="pure-control-group">';
   echo '<label for="gen">Succession #: </label>';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   echo "\n<option value='".$egen."'>".$egen."</option>";
   for ($i = 0; $i < 31; $i++) {
     echo "\n<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
}
?>

