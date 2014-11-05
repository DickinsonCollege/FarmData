<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select units from plant where crop= '".escapehtml($_GET['crop'])."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
   echo $row['units'];
}
?>

