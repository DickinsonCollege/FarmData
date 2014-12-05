<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sYear = $_GET['sYear'];
$org = $_GET['org'];
include 'make_code.php';
echo $code;
?>

