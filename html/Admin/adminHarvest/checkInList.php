<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT * FROM harvestListItem where id=".$_GET['id']." and crop='".
 escapehtml($_GET['crop'])."'";
$result=$dbcon->query($sql);

$values=array();
while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
   $values[str_replace(" ", "_",$row['target'])]=$row['amt'];
   $values['fieldID'] = $row['fieldID'];
   $values[str_replace(" ", "_",$row['target']).'_unit']=$row['units'];
}
echo json_encode($values);
?>
