<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$material = escapehtml($_GET['material']);
$sql="Select distinct unit from compost_units where material='".$material."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
   echo "<option value=\"".$row['unit']."\">".$row['unit']."</option>";
}
?>

