<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$both = escapehtml($_GET['both']);
$fieldID = escapehtml($_GET['fieldID']);
$sql = "SELECT seedDate from coverSeed  where (crop1 = '".$both."' or crop2= '".$both.
   "') and (year(seedDate)=year(now()) or year(seedDate)=year(now())-1) and fieldID ='".$fieldID."'; " ;
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
   echo "<option value=\"".$row['seedDate']."\">".$row['seedDate']."</option>";
}
mysql_close();
?>

