<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
header("Content-type: application/octet-stream");
header("Content-disposition: attachment;filename=\"report.csv\"");
$flds = unserialize($_POST['fields']);
$vals = unserialize($_POST['vals']);
$year = $_POST['year'];
$tyear = $_POST['tyear'];

echo '"Year",';
foreach ($flds as $fieldID) {
   echo '"'.htmlspecialchars_decode($fieldID, ENT_QUOTES).'",';
}
echo "\n";
for ($yr = $tyear; $yr >= $year; $yr--) {
   echo '"'.$yr.'",';
   foreach ($flds as $fieldID) {
      $val = str_replace("<b>", "", htmlspecialchars_decode($vals[$yr][$fieldID], ENT_QUOTES));
      $val = str_replace("<br>", ";", $val);
      $val = str_replace("</b>", "", $val);
      $val = str_replace("&nbsp;", " ", $val);
      echo '"'.trim($val).'",';
   }
   echo "\n";
}
?>
