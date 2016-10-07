<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];
$sql = "SELECT distinct fieldID from harvested  where crop like '".
  $crop."' and hardate between '".$sdate."' and '".$edate."'";
$result = $dbcon->query($sql);
echo "<option value='%'> All </option>";
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
?>

