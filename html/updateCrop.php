<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$typ = $_GET['typ'];
if ($typ == "harvesting") {
  $sql = "select crop from (SELECT crop from dir_planted where year(plantdate) = '".$_GET['year'].
     "' union SELECT crop from transferred_to where year(transdate)= '".$_GET['year']."') as crp order by crop";
} else if ($typ == "labor") {
  $sql = "SELECT crop from dir_planted where year(plantdate) = '".$_GET['year']."' union SELECT crop from gh_seeding where year(seedDate)= '".$_GET['year']."'";
} else if ($typ == "transplanting") {
   $sql = "SELECT distinct crop from gh_seeding where year(seedDate) = '".$_GET['year']."' order by crop";
} else {
   $sql = "SELECT distinct crop from plant order by crop";
}
$result = mysql_query($sql);
if ($typ == "labor") {
   echo "\n<option value= \"N/A\">N/A</option>";
}
while($row = mysql_fetch_array($result)) {
   echo "\n<option value= \"$row[crop]\">$row[crop]</option>";
}
mysql_close();
?>
