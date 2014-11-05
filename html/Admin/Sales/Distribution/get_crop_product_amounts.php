<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$cropProd = escapehtml($_GET['cropProd']);
$grade = escapehtml($_GET['grade']);

$sql =  "SELECT amount, unit FROM inventory WHERE crop_product='".$cropProd."' AND grade=".$grade;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$amount = $row['amount'];
$unit = $row['unit'];

$amountUnitArray = array();

$sql = "SELECT conversion FROM units WHERE crop='".$cropProd."' AND unit='POUND'";
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
   $amountUnitArray[0] = $amount;
   $amountUnitArray[1] = $unit;
} else {
   $row = mysql_fetch_array($result);
   $conversion = $row[0];
   $amountUnitArray[0] = $amount * $conversion;
   $amountUnitArray[1] = 'POUND';
}

echo json_encode($amountUnitArray);

mysql_close();
?>
