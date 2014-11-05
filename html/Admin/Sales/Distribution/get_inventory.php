<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$cropProd = $_GET['cropProd'];
$grade = $_GET['grade'];

$sql = "SELECT * FROM inventory WHERE crop_product='".$cropProd."' AND grade=".$grade;
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$amountUnitArray = array();
$amountUnitArray[0] = round($row['amount'], 2);
$amountUnitArray[1] = $row['unit'];

if ($amountUnitArray[1] == null) {
	$amountUnitArray[0] = 0;
	$amountUnitArray[1] = "UNIT";
} else {
   $convsql = "SELECT conversion FROM units WHERE crop='".$cropProd."' AND unit='POUND'";
   $convresult = mysql_query($convsql);
   if (mysql_num_rows($convresult) > 0) {
      $convrow = mysql_fetch_array($convresult);
      $conversion = $convrow[0];
      $amountUnitArray[0] = round($row['amount'] * $conversion, 2);
      $amountUnitArray[1] = 'POUND';
   }
}

echo json_encode($amountUnitArray);

mysql_close();
?>
