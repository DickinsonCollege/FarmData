<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select * from field_GH where fieldID = '".escapehtml($_GET['fieldID'])."'";
$result=mysql_query($sql);
$arr = array();
$row=mysql_fetch_array($result);
if ($row) {
  $arr = array($row['size'], $row['numberOfBeds'], $row['length'], $row['active']);
}
echo json_encode($arr);
?>

