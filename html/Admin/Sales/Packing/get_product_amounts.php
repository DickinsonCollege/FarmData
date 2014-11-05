<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$product = escapehtml($_GET['product']);

$sql = "SELECT * FROM product WHERE product='".$product."'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$amountArray = array();

$amountArray[0] = $row['units_per_case'];
$amountArray[1] = $row['unit'];

echo json_encode($amountArray);

mysql_close();
?>
