<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$farm = escapehtml($_GET['farm']);
if ($farm == 'wahlst_spiralpath') {
  echo $sql = "select distinct unit as units from extUnits";
} else {
   echo $sql="Select distinct unit as units from units where crop='".$crop."'";
}
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
   echo "<option value=\"".$row['units']."\">".$row['units']."</option>";
}
?>

