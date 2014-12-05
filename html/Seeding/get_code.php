<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "SELECT code, variety from seedInventory where crop = '".$crop.
    "' order by code";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
   // echo "<option value=\"".$row['code']." (".$row['variety'].")\">".$row['code']." (".$row['variety'].")</option>";
   echo "<option value=\"".$row['code']."\">".$row['code']." (".$row['variety'].")</option>";
}
mysql_close();
?>

