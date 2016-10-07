<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);

$sql = "select fieldID from (SELECT fieldID from dir_planted natural join field_GH where active = 1 ".
          "and crop like '".$crop."' ".
          " and '".$_GET['harvDate']."' between plantdate and lastHarvest".
          " union ".
       "SELECT fieldID from transferred_to natural join field_GH where active = 1 and crop = '".
          $crop."' and '".$_GET['harvDate']."' between transdate and lastHarvest) as tmp order by fieldID ";
$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}

?>

