<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "SELECT code, variety from seedInventory where crop = '".$crop.
    "' and inInventory > 0 order by code";
$result = $dbcon->query($sql);
echo "<option value=\"N/A\">Not Available</option>";
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
   // echo "<option value=\"".$row['code']." (".$row['variety'].")\">".$row['code']." (".$row['variety'].")</option>";
   echo "<option value=\"".$row['code']."\">".$row['code']." (".$row['variety'].")</option>";
}
?>

