<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);
if ($fieldID == "N/A") {
   $sql = "select crop from plant where active=1 order by crop";
} else {
   $sql = "select crop from (select crop from dir_planted  where fieldID = '".$fieldID.
      "' and '".$_GET['laborDate']."' between plantdate and lastHarvest ".
      " union select crop from transferred_to where fieldID = '".$fieldID."' and ".
      " '".$_GET['laborDate']."' between transdate and lastHarvest) as crp order by crop";
}
$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['crop']."\">".$row['crop']."</option>";
}
?>

