<?php 
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$user = escapehtml($_GET['user']);
$sql="select admin, active from users where username='".$user."'";
$result = $dbcon->query($sql);
$info = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $info['admin'] = $row['admin'];
   $info['active'] = $row['active'];
}
echo json_encode($info);
?>

