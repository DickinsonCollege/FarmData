<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT * FROM harvestListItem where id=".$_GET['id']." and crop='".
 escapehtml($_GET['crop'])."'";
$result=mysql_query($sql);

$values=array();
while ($row=mysql_fetch_array($result)) {
   $values[str_replace(" ", "_",$row['target'])]=$row['amt'];
   $values['fieldID'] = $row['fieldID'];
/*
   if(isset($row['crop'])){

	$fieldID=escapeescapehtml($row['fieldID']);
	$cas=$row['CSA'];
	$dining=$row['dining'];
	$market=$row['market'];
	$other=$row['other'];
	$total=$row['Total'];
      $values = array($fieldID,$cas,$dining,$market,$other,$total);
      echo json_encode($values);
   }
*/
}
echo json_encode($values);
?>
