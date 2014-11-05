<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fieldID = escapehtml($_GET['fieldID']);

$sql = "SELECT distinct seedDate 
		FROM coverSeed_master NATURAL JOIN coverSeed 
		WHERE fieldID='".$fieldID."' 
		ORDER BY seedDate";

$result = mysql_query($sql);
while($row = mysql_fetch_array($result)) {
   echo "<option value=\"".$row['seedDate']."\">".$row['seedDate']."</option>";
}

mysql_close();
?>

