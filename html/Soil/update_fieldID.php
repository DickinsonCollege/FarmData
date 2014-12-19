<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

//$crop = escapehtml($_GET['crop']);
$sql = "SELECT distinct fieldID from field_GH where active=1 and fieldID not in (select fieldID from field_irrigation)";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
mysql_close();
?>

