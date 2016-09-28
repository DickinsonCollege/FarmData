<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$material = escapehtml($_GET['material']);
$sql="Select distinct unit from compost_unit where unit not in ".
   "(Select unit from compost_units where material='".$material."')";
$result = $dbcon->query($sql);
while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['unit']."\">".$row['unit']."</option>";
}
?>

