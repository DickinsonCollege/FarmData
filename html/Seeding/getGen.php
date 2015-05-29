<?php
if ($_SESSION['gens']) {
   echo '<div class="pure-control-group" id="genDiv">';
   echo '<label for="gen">Succession #:</label> ';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   for ($i = 0; $i < 31; $i++) {
     echo "<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
   // echo '<br clear="all"/>';
}
?>

