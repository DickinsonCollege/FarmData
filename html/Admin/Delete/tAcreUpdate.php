<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT size*(".escapehtml($_GET['beds']).
   "/numberOfBeds) AS totalSpray FROM field_GH where fieldID='".
   escapehtml($_GET['field'])."'";
$result=$dbcon->query($sql);
$row=$result->fetch(PDO::FETCH_ASSOC);
echo number_format($row['totalSpray'],2,'.','');
?>

