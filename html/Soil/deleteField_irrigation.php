<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = $_GET['fieldID'];
$sql 		= "delete from field_irrigation where fieldID='".$fieldID."'";
try {
   $stmt = $dbcon->prepare($sql);
   $stmt->execute();
} catch (PDOException $p) {
   die($p->getMessage());
}
?>
