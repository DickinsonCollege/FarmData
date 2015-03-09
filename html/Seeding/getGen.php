<?php
if ($_SESSION['gens']) {
   echo '<label for="gen"> Succession #:&nbsp; </label>';
   echo '<div id="genDiv" class="styled-select">';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   for ($i = 1; $i < 16; $i++) {
     echo "\n<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all">';
}
?>

