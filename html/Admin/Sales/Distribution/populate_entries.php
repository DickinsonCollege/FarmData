<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$packDate = escapehtml($_GET['packDate']);

$sql = "SELECT * FROM pack WHERE packDate='".$packDate."' ORDER BY crop_product, grade";
$result = $dbcon->query($sql);

$packArray = array();
$i = 0;

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $rowArray = array();

   $rowArray[0] = $row['Target'];
   $rowArray[2] = $row['crop_product'];
   $rowArray[4] = $row['grade'];
   $convsql = "SELECT conversion FROM units WHERE crop='".$row['crop_product']."' AND unit='POUND'";
   $convresult = $dbcon->query($convsql);
   if ($convrow = $convresult->fetch(PDO::FETCH_ASSOC)) {
      $conversion = $convrow['conversion'];
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

?>
