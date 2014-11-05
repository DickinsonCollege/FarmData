<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);

$sqlGetTime = 'select start_time from field_irrigation where fieldID=\''.$fieldID.'\'';
$data = mysql_query($sqlGetTime) or die(mysql_error());
$row    = mysql_fetch_array($data);
$start= $row['start_time'];

if ($start == "") {
   $time 	= time();
   $sql 		= "update field_irrigation set start_time=".$time." where fieldID='".$fieldID."'";
   mysql_query($sql) or die(mysql_error());
   echo mysql_error();
}
?>
