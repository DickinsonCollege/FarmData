<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT * FROM harvestListItem where id=".$_GET['id']." and crop='".
 escapehtml($_GET['crop'])."'";
$result=mysql_query($sql);

$values=array();
while ($row=mysql_fetch_array($result)) {
   $values[str_replace(" ", "_",$row['target'])]=$row['amt'];
   $values['fieldID'] = $row['fieldID'];
}
echo json_encode($values);
?>
