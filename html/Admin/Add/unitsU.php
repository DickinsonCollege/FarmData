<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql="Select distinct unit from extUnits where unit not in (Select unit from units where crop='".$crop."')";
$result=$dbcon->query($sql);
while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['unit']."\">".$row['unit']."</option>";
}
?>

