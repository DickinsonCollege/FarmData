<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$user = escapehtml($_GET['user']);
$sql="select admin, active from users where username='".$user."'";
$result=mysql_query($sql);
$info = array();
while ($row=mysql_fetch_array($result)) {
   $info['admin'] = $row['admin'];
   $info['active'] = $row['active'];
}
echo json_encode($info);
?>

