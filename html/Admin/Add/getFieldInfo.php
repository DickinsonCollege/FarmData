<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select * from field_GH where fieldID = '".escapehtml($_GET['fieldID'])."'";
$result = $dbcon->query($sql);
$arr = array();
$row = $result->fetch(PDO::FETCH_ASSOC);
if ($row) {
  $arr = array($row['size'], $row['numberOfBeds'], $row['length'], $row['active']);
}
echo json_encode($arr);
?>

