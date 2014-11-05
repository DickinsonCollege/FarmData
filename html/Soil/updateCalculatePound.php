<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);
$percent = escapehtml($_GET['percent']);
// $sql="Select ('".$_GET['percent']."'/'100')*(Select size from field_GH where fieldID='".$fieldID."') as size";
$area=mysql_query("Select ('".$percent."'/'100')*(Select size from field_GH where fieldID='".
   $fieldID."') as size");
$row=mysql_fetch_array($area);
$size=$row['size'];
echo number_format($size*$_GET['rate'],1,'.','');


?>
