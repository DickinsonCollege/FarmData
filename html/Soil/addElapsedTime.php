<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);
$sqlGetTime = 'select start_time from field_irrigation where fieldID=\''.$fieldID.'\'';
$data = mysql_query($sqlGetTime) or die(mysql_error());
$row 	= mysql_fetch_array($data);
$start= $row['start_time'];
if ($start != "") {
  $current = time();
  $amount 	= ($current - $start)/60;
  $sql 		= "update field_irrigation set elapsed_time = elapsed_time + ".$amount.", start_time=NULL  where fieldID='".$fieldID."'";
  echo $sql;
  mysql_query($sql) or die(mysql_error());
  echo mysql_error();
}
?>
