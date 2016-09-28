<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$sql = "SELECT distinct fieldID from harvested  where crop like '".
  $crop."' and year(hardate) between '".$_GET['year']."' and '".
  $_GET['tyear']."'";
$result = $dbcon->query($sql);
echo "<option value='%'> All </option>";
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
?>

