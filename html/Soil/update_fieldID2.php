<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop1 = escapehtml($_GET['crop1']);
$crop2 = escapehtml($_GET['crop2']);
$sql = "SELECT distinct fieldID from coverSeed where crop1 like '".$crop1."' and crop2 like '".$crop2.
  "' and year(seedDate) between '".$_GET['year']."' and '".$_GET['tyear']."'";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
   echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
mysql_close();
?>

