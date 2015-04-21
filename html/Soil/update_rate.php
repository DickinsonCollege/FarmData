<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "Select BRateMin as min, BRateMax as max, BRateDefault as default2 from tSprayMaterials where sprayMaterial = '".escapehtml($_GET['material'])."' ";
$result = mysql_query($sql);
$row1 = mysql_fetch_array($result);
$def = $row1['default2'];
echo "\n<option value= '".$def."'>".$def."</option>";
$total = $row1['min'];
while($total <= $row1['max']) {
  echo "\n<option value= '".$total."'>".$total."</option>";
  $total = $total + .25;
}
mysql_close();
?>

