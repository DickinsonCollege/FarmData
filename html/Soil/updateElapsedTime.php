<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);

$sqlGetTime = 'select start_time from field_irrigation where fieldID=\''.$fieldID.'\'';
$data = $dbcon->query($sqlGetTime);
$row    = $data->fetch(PDO::FETCH_ASSOC);
$start= $row['start_time'];

if ($start == "") {
   $time 	= time();
   $sql 		= "update field_irrigation set start_time=".$time." where fieldID='".$fieldID."'";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      die($p->getMessage());
   }
}
?>
