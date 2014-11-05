<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT numberOfBeds from field_GH where fieldID='".escapehtml($_GET['fieldID'])."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$num= 1;
while($num <= $row['numberOfBeds']) {
   echo "<option value=\"".$num."\">".$num."</option>";
   $num++;
}
mysql_close();
?>

