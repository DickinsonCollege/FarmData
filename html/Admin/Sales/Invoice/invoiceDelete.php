<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$delete="delete from invoice_entry where invoice_no='".$_GET['invoice']."'";
try {
   $stmt = $dbcon->prepare($delete);
   $stmt->execute();
} catch (PDOException $p) {
   die($p->getMessage());
}
$delete2="delete from invoice_master where invoice_no='".$_GET['invoice']."'";
try {
   $stmt = $dbcon->prepare($delete2);
   $stmt->execute();
} catch (PDOException $p) {
   die($p->getMessage());
}
echo ' <meta http-equiv="refresh" content=0;URL="invoiceChooseDate.php?exist=1&tab=admin:admin_sales:invoice:editinvoice">';


?>

