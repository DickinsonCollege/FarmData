<?php
if ($_SESSION['gens']) {
   echo '<label for="gen"> Generation #:&nbsp; </label>';
   echo '<div id="genDiv" class="styled-select">';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   echo "\n<option value='".$egen."'>".$egen."</option>";
   for ($i = 1; $i < 11; $i++) {
     echo "\n<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all">';
}
?>

