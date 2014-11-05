<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="Select active from field_GH where fieldID = '".escapehtml($_GET['fieldID'])."'";
$result=mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($result);
$active = $row['active'];
echo $active;
if ($active == 1) {
   echo '<option value=1>Yes</option>';
   echo '<option value=0>No</option>';
} else {
   echo '<option value=0>No</option>';
   echo '<option value=1>Yes</option>';
}
?>

