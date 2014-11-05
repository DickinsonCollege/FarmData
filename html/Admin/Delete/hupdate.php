<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql="Select distinct unit from units where crop='".$crop."' AND UNIT not in (Select units from plant where crop='".$crop."')";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
echo "<option value=\"".$row['unit']."\">".$row['unit']."</option>";
}
?>

