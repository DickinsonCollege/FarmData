<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fieldID = escapehtml($_GET['fieldID']);

$sql = "SELECT distinct seedDate 
		FROM coverSeed_master NATURAL JOIN coverSeed 
		WHERE fieldID='".$fieldID."' 
		ORDER BY seedDate";

$result = $dbcon->query($sql);
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['seedDate']."\">".$row['seedDate']."</option>";
}

?>

