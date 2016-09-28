<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "Select BRateMin as min, BRateMax as max, BRateDefault as default2 from tSprayMaterials ".
  "where sprayMaterial = '".escapehtml($_GET['material'])."' ";
$result = $dbcon->query($sql);
$row1 = $result->fetch(PDO::FETCH_ASSOC);
$def = $row1['default2'];
echo "\n<option value= '".$def."'>".$def."</option>";
$total = $row1['min'];
while($total <= $row1['max']) {
  echo "\n<option value= '".$total."'>".$total."</option>";
  $total = $total + .25;
}
?>

