<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
$sql = "SELECT sum(flats) as numflats from gh_seeding  where crop = '".
   $crop."' and seedDate = '".$_GET['date']."'";
$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo $row['numflats'];
}
?>

