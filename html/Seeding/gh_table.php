<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';


if (isset($_GET['id'])) {
   $sqlDel="DELETE FROM gh_seeding WHERE id=".$_GET['id'];
   mysql_query($sqlDel);
   echo mysql_error();
}
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$genSel = $_GET['genSel'];
$crop = escapehtml($_GET['crop']);

$sql="Select id, username, seedDate,crop,numseeds_planted,flats,cellsFlat,varieties,gen, comments from gh_seeding where crop like '".
   $crop."' and gen like '".$genSel."' and seedDate between '".$year."-".$month."-".$day."' AND '".
   $tcurYear."-".$tcurMonth."-".$tcurDay."' order by seedDate ";
if ($crop!="%") {
   $total = "select sum(numseeds_planted) as totalSum from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."' and gen like '".$genSel."'";
   $totalf = "select sum(flats) as totalFlats from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."' and gen like '".$genSel."'";
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
echo "<caption> Tray Seeding Records for: ".$crp;
if ($_SESSION['gens']) {
   if ($genSel == "%") {
      echo " of All Successions";
   } else {
      echo " of Succession ".$genSel;
   }
}
echo "</caption>";
echo "<tr><th>Date of Seeding</th><th>Crop</th>";
if (!$_SESSION['bigfarm']) {
   echo "<th>Number of Seeds</th>";
}
echo "<th>Trays</th><th>Cells/Tray</th><th>Varieties</th>";
if ($_SESSION['gens']) {
   echo "<th>Succ&nbsp;#</th>";
}
echo "<th> Comments</th>";
if ($_SESSION['admin']) {
   echo "<th>User</th><th>Edit</th><th>Delete</th>";
}
echo "</tr>";
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
   if ($_SESSION['gens']) {
        echo $row['gen'];
        echo "</td><td>";
   }
   echo $row['comments'];
   echo "</td>";
   if ($_SESSION['admin']) {
      echo "<td>".$row['username']."</td>";
      echo "<td><form method='POST' action=\"ghEdit.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
      "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop'])."&genSel=".$genSel.
      "&tab=seeding:flats:flats_report&submit=Submit\">";
      echo "<input type='submit' class='editbutton' value='Edit'";
      echo 'onclick="return show_warning();">';
      echo "</form></td>";

      echo "<td><form method='POST' action=\"gh_table.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
      "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop'])."&genSel=".$genSel.
      "&tab=seeding:flats:flats_report&submit=Submit\">";
      echo "<input type='submit' class='deletebutton' value='Delete'";
      echo 'onclick="return show_delete_warning();">';
      echo "</form></td>";
   }
   echo "</tr>";
}
echo "</table>";
if($crop != '%' && !$_SESSION['bigfarm']) {
     echo "<br clear=\"all\"/>";
     $totalResult = mysql_query($total);
     echo mysql_error();
     while($row5 = mysql_fetch_array($totalResult)){
        echo '<label for="total"> Total Number of Seeds Planted:&nbsp;</label>';
   echo ' <input type="textbox" name="total" style="float: left;width: 100px;" id="total" class="textbox2" disabled value='.$row5['totalSum'].'>';
     }
     echo "<br clear=\"all\"/>";
     $totalResult = mysql_query($totalf);
     echo mysql_error();
     while($row5 = mysql_fetch_array($totalResult)){
        echo '<label for="total"> Total Number of Trays Planted:&nbsp;</label>';
   echo ' <input type="textbox" name="total" style="float: left;width: 100px;" id="total" class="textbox2" disabled value='.$row5['totalFlats'].'>';
     }
}
        echo "<br clear=\"all\"/>";
        if($crop != "%") {
        echo "<br clear=\"all\"/>";
        }
   echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
   echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '<form method="POST" action = "/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

?>
