<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql = "SELECT distinct fieldID from bspray where materialSprayed like '".
   escapehtml($_GET['sprayMaterial']).
   "' and year(sprayDate) between '".$_GET['year']."' and '".$_GET['tyear']."'";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
mysql_close();
?>

