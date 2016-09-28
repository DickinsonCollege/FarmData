<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT distinct fieldID from bspray where materialSprayed like '".
   escapehtml($_GET['sprayMaterial']).
   "' and year(sprayDate) between '".$_GET['year']."' and '".$_GET['tyear']."'";
$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
?>

