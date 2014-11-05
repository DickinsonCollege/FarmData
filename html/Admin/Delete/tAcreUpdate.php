<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT size*(".escapehtml($_GET['beds']).
   "/numberOfBeds) AS totalSpray FROM field_GH where fieldID='".
   escapehtml($_GET['field'])."'";
$result=mysql_query($sql);
echo mysql_error();
$row=mysql_fetch_array($result);
echo number_format($row['totalSpray'],2,'.','');
?>

