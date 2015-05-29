<?php 
include $_SERVER['DOCUMENT_ROOT'].'/escapehtml.php';
$user = escapehtml($_GET['user']);
$dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or
    die ("Connect Failed! :".mysql_error());
mysql_select_db('wahlst_users');
$sql="select admin, active from users where username='".$user."'";
   $result = mysql_query($sql);

$info = array();
while ($row=mysql_fetch_array($result)) {
   $info['admin'] = $row['admin'];
   $info['active'] = $row['active'];
}
echo json_encode($info); 
?>

