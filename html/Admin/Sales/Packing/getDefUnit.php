<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql="select distinct units from plant where crop='".$crop.
   "' union select distinct unit from product where product='".$crop."'";
$result=$dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo $row['units'];
}
?>

