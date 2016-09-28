<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$variety = escapehtml($_GET['variety']);

$sql = "SELECT * from orderItem where crop = '".$crop."' and variety = '".
  $variety."' and sdate3 >= all (select sdate3 from orderItem where crop = '".
  $crop."' and variety = '".$variety."')";
$result = $dbcon->query($sql);
if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo json_encode($row); 
} else {
   echo "";
}

?>
