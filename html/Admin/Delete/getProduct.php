<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$product = escapehtml($_GET['product']);
$sql = "select unit, units_per_case, dh_units, active from product where product='".$product."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$plantarray = array();
$plantarray[0] = $row['unit'];
$plantarray[1] = $row['units_per_case'];
$plantarray[2] = $row['dh_units'];
$plantarray[3] = $row['active'];
echo json_encode($plantarray);
?>
