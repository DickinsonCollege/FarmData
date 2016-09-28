<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT numberOfBeds from field_GH where fieldID='".escapehtml($_GET['fieldID'])."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$num= 1;
while($num <= $row['numberOfBeds']) {
   echo "<option value=\"".$num."\">".$num."</option>";
   $num++;
}
?>

