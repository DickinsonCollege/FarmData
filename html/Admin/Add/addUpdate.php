<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select units from plant where crop= '".escapehtml($_GET['crop'])."'";
$result=$dbcon->query($sql);
while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
   echo $row['units'];
}
?>

