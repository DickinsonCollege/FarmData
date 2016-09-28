<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);
$percent = escapehtml($_GET['percent']);
$area=$dbcon->query("Select ('".$percent."'/'100')*(Select size from field_GH where fieldID='".
   $fieldID."') as size");
$row=$area->fetch(PDO::FETCH_ASSOC);
$size=$row['size'];
echo number_format($size*$_GET['rate'],1,'.','');


?>
