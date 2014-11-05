<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT size*(".$_GET['percent']."/100) as area from field_GH  where fieldID = '".$_GET['fieldID']."'";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
 echo round($row['area'], 2);
}
?>

