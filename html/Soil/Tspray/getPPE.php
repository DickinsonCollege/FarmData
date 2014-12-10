<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$material = escapehtml($_GET['material']);

$sql = "SELECT PPE FROM tSprayMaterials WHERE sprayMaterial='".$material."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
echo $row['PPE'];

/*
$ppe = explode(",", $row[0]);

echo "<option value='0' selected disabled>PPE</option>";
foreach ($ppe as $equip) {
   echo "<option value='".$equip."' disabled>".$equip."</option>";
}
*/

?>
