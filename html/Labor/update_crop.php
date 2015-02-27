<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = escapehtml($_GET['fieldID']);
if ($fieldID == "N/A") {
   $sql = "select crop from plant where active=1 order by crop";
} else {
   $sql = "select crop from (select crop from dir_planted  where fieldID = '".$fieldID."' and year(plantdate) = '".$_GET['plantyear']."' union select crop from transferred_to where fieldID = '".$fieldID."' and year(transdate) = '".$_GET['plantyear']."') as crp order by crop";
}
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
echo "<option value=\"".$row['crop']."\">".$row['crop']."</option>";
}
mysql_close();
?>

