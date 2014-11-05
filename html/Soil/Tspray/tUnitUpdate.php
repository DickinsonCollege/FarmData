<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT TRateUnits  FROM tSprayMaterials  where sprayMaterial='".
   escapehtml($_GET['material'])."'";
$result=mysql_query($sql);
//echo mysql_error();
while ($row=mysql_fetch_array($result)) {
echo $row['TRateUnits'];

}
?>

