<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "select units, units_per_case, dh_units, active from plant where crop='".$crop."'";
$result = $dbcon->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);

$plantarray = array();
$plantarray[0] = $row['units'];
$plantarray[1] = $row['units_per_case'];
$plantarray[2] = $row['dh_units'];
$plantarray[3] = $row['active'];
echo json_encode($plantarray);
?>
