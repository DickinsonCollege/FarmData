<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
$farm = $_SESSION['db'];
?>

<form name='form' method='POST' action='/down.php'>

<?php
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$tcurYear = $_POST['tyear'];
$tcurMonth = $_POST['tmonth'];
$tcurDay = $_POST['tday'];
$crop = escapehtml($_POST['crop']);
$sql="Select seedDate,crop,numseeds_planted,flats,cellsFlat,varieties,comments from gh_seeding where crop like '".
   $crop."' and seedDate between '".$year."-".$month."-".$day."' AND '".
   $tcurYear."-".$tcurMonth."-".$tcurDay."' order by seedDate ";
if ($crop!="%") {
   $total = "select sum(numseeds_planted) as totalSum from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."'";
   $totalf = "select sum(flats) as totalFlats from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."'";
}
echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';

$result=mysql_query($sql);
//$parts = explode("/* delimiter */", $sql);
if ($crop == '%') {
   $crp = 'All Crops';
} else {
   $crp = $_POST['crop'];
}
echo "<table border>";
echo "<caption> Flats Seeding Records for: ".$crp."</caption>";
echo "<tr><th>Plant Date</th><th>Crop</th>";
if (!$_SESSION['bigfarm']) {
   echo "<th>#seeds</th>";
}
echo "<th>Flats</th><th>Cells/Flat</th><th>Varieties</th><th> Comments</th></tr>";
while ( $row = mysql_fetch_array($result)) {
   echo "<tr><td>";
   echo $row['seedDate'];
   echo "</td><td>";
   echo $row['crop'];
   echo "</td><td>";
   if (!$_SESSION['bigfarm']) {
        echo $row['numseeds_planted'];
        echo "</td><td>";
   }
   echo $row['flats'];
   echo "</td><td>";
   echo $row['cellsFlat'];
   echo "</td><td>";
   echo $row['varieties'];
   echo "</td><td>";
   echo $row['comments'];
   echo "</td></tr>";
}
echo "</table>";
if($crop != '%' && !$_SESSION['bigfarm']) {
     echo "<br clear=\"all\"/>";
     $totalResult = mysql_query($total);
     echo mysql_error();
     while($row5 = mysql_fetch_array($totalResult)){
        echo '<label for="total"> Total Seeds Planted:&nbsp;</label>';
	echo ' <input type="textbox" name="total" style="float: left;width: 100px;" id="total" class="textbox2" disabled value='.$row5['totalSum'].'>';
     }
     echo "<br clear=\"all\"/>";
     $totalResult = mysql_query($totalf);
     echo mysql_error();
     while($row5 = mysql_fetch_array($totalResult)){
        echo '<label for="total"> Total Flats  Planted:&nbsp;</label>';
	echo ' <input type="textbox" name="total" style="float: left;width: 100px;" id="total" class="textbox2" disabled value='.$row5['totalFlats'].'>';
     }
}
        echo "<br clear=\"all\"/>";
        if($crop != "%") {
        echo "<br clear=\"all\"/>";
        }
	echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '<form method="POST" action = "/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
?>
