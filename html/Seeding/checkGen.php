<?php
if ($_SESSION['gens']) {
  echo 'var gen = document.getElementById("gen").value;';
  echo 'con += "Generation #: " + gen + "\n";';
}
?>
