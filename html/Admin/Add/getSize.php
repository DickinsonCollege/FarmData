<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select size from field_GH where fieldID = '".escapehtml($_GET['fieldID'])."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
   echo "value=\"".$row['size']."\"";
}
?>

