<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$crop = escapehtml($_GET['crop']);
$fld = escapehtml($_GET['fieldID']);

$sql = "select gen from (SELECT gen from dir_planted where crop like '".$crop."' and ".
   "'".$_GET['harvDate']."' between plantdate and lastHarvest ".
   " and fieldID = '".$fld."' union ".
" SELECT gen from transferred_to where crop = '".$crop."' and ".
   "'".$_GET['harvDate']."' between transdate and lastHarvest ".
   " and fieldID = '".$fld."') as tmp order by gen";

$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['gen']."\">".$row['gen']."</option>";
}

?>

