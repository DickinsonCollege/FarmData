<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select distinct unit from extUnits";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
echo "<option value=\"".$row['unit']."\">".$row['unit']."</option>";
}
?>

