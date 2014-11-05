<?php
$sql = "show columns from config";
$res = mysql_query($sql);
echo mysql_error();
$ids = array();
while ($row = mysql_fetch_array($res)) {
   $ids[] = $row['Field'];
}
$sql = "select * from config";
$res = mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($res);
for ($i = 0; $i < count($ids); $i++) {
   $_SESSION[$ids[$i]] = $row[$ids[$i]];
}
//print_r($_SESSION);
?>
