<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$variety = escapehtml($_GET['variety']);

$sql = "SELECT * from orderItem where crop = '".$crop."' and variety = '".
  $variety."' and sdate3 >= all (select sdate3 from orderItem where crop = '".
  $crop."' and variety = '".$variety."')";
$result = mysql_query($sql);
if ($row = mysql_fetch_array($result)) {
   echo json_encode($row); 
} else {
   echo "";
}

mysql_close();
?>
