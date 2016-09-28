<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT invoice_entry.invoice_no, product, cases, price_case, salesDate ".
   "FROM invoice_entry, invoice_master where product= '".
   escapehtml($_GET['product'])."' and target = '".escapehtml($_GET['target']).
   "' and invoice_entry.invoice_no= invoice_master.invoice_no order by salesDate desc";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
if (!$row) {
   echo "0,0";
} else if ($row['invoice_no']>0) {
   if($_GET['currentID']==$row['invoice_no']){
      echo $row['cases'].",";
      echo $price_case=$row['price_case'];
   }else{
      echo "0,";
      echo $price_case=$row['price_case'];   
   }
}
?>
