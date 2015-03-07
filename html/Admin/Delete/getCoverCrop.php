<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "select * from coverCrop where crop='".$crop."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$plantarray = array();
$plantarray[0] = $row['drillRateMin'];
$plantarray[1] = $row['drillRateMax'];
$plantarray[2] = $row['brcstRateMin'];
$plantarray[3] = $row['brcstRateMax'];
$plantarray[4] = $row['legume'];
$plantarray[5] = $row['active'];
echo json_encode($plantarray);
?>
