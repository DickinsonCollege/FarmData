<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$id = $_GET['id'];
$inits = escapehtml($_GET['init']);
$sql = "update tSprayMaster set complete=1, initials='".$inits."' where id = ".$id;
try {
   $stmt = $dbcon->prepare($sql);
   $stmt->execute();
} catch (PDOException $p) {
   die($p->getMessage());
}
?>

