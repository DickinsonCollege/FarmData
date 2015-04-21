<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
# $sql="Select distinct units from plant where crop='".$crop."'";
$sql="Select distinct unit as units from units where crop='".$crop."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
   echo "<option value=\"".$row['units']."\">".$row['units']."</option>";
}
?>

