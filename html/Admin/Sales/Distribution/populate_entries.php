<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$packDate = escapehtml($_GET['packDate']);

$sql = "SELECT * FROM pack WHERE packDate='".$packDate."' ORDER BY crop_product, grade";
$result = mysql_query($sql);

$packArray = array();
$i = 0;

while ($row = mysql_fetch_array($result)) {
   $rowArray = array();

   $rowArray[0] = $row['Target'];
   $rowArray[2] = $row['crop_product'];
   $rowArray[4] = $row['grade'];
   $convsql = "SELECT conversion FROM units WHERE crop='".$row['crop_product']."' AND unit='POUND'";
   $convresult = mysql_query($convsql);
   if (mysql_num_rows($convresult) > 0) {
      $convrow = mysql_fetch_array($convresult);
      $conversion = $convrow[0];
      $rowArray[1] = (float) $row['amount'] * $conversion;
      $rowArray[3] = 'POUND';
   } else {
      $rowArray[1] = $row['amount'];
      $rowArray[3] = $row['unit'];
   }

   $packArray[$i] = $rowArray;
   $i++;
}

echo json_encode($packArray);

mysql_close();
?>
