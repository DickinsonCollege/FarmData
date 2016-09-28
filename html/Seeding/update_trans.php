<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);
// should this be the transplant year rather than the current year???
$sql = "SELECT distinct seedDate from gh_seeding  where crop = '".$crop.
    "' and year(seedDate)=year(now()) order by seedDate";
$result = $dbcon->query($sql);
echo '<option value="0000-00-00">N/A</option>';
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['seedDate']."\">".$row['seedDate']."</option>";
}
?>

