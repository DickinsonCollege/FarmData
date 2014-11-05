<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "SELECT sum(flats) as numflats from gh_seeding  where crop = '".
   $crop."' and seedDate = '".$_GET['date']."'";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
    echo $row['numflats'];
}
mysql_close();
?>

