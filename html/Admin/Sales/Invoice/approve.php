<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$by = escapehtml($_GET['by']);
$sql="update invoice_master set approved_by= '".$by."' where invoice_no='".$_GET['invoice'].
   "' and salesDate='".$_GET['salesDate']."'";
try {
   $stmt = $dbcon->prepare($sql);
   $stmt->execute();
} catch (PDOException $p) {
   die($p->getMessage());
}
?>
