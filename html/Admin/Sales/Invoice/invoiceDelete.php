<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$delete=mysql_query("delete from invoice_entry where invoice_no='".
   $_GET['invoice']."'");
$delete2=mysql_query("delete from invoice_master where invoice_no='".
   $_GET['invoice']."'");
echo ' <meta http-equiv="refresh" content=0;URL="invoiceChooseDate.php?exist=1&tab=admin:admin_sales:invoice:editinvoice">';


?>

