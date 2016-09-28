<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';


if (isset($_GET['id'])) {
   $sqlDel="DELETE FROM gh_seeding WHERE id=".$_GET['id'];
   try {
      $stmt = $dbcon->prepare($sqlDel);
      $stmt->execute();
   } catch (PDOException $p) {
      die($p->getMessage());
   }
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
   $tcurYear."-".$tcurMonth."-".$tcurDay."' order by seedDate";
if ($crop!="%") {
   $total = "select sum(numseeds_planted) as totalSum from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."' and gen like '".$genSel."'";
   $totalf = "select sum(flats) as totalFlats from gh_seeding where seedDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' AND crop ='".$crop."' and gen like '".$genSel."'";
}
echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';

try {
   $result=$dbcon->query($sql);
} catch (PDOException $p) {
   phpAlert('', $p);
}
//$parts = explode("/* delimiter */", $sql);
if ($crop == '%') {
   $crp = 'All Crops';
} else {
   $crp = $_GET['crop'];
}
echo "<center>";
echo "<h2> Tray Seeding Records for: ".$crp;
if ($_SESSION['gens']) {
   if ($genSel == "%") {
      echo " of All Successions";
   } else {
      echo " of Succession ".$genSel;
   }
}
echo "</h2>";
echo "</center>";
echo "<table class='pure-table pure-table-bordered'>";
echo "<thead><tr><th>Date of Seeding</th><th>Crop</th>";
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
echo "</tr></thead>";
while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
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
      echo "<input type='submit' class='editbutton pure-button wide' value='Edit'";
      echo 'onclick="return show_warning();">';
      echo "</form></td>";

      echo "<td><form method='POST' action=\"gh_table.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
      "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop'])."&genSel=".$genSel.
      "&tab=seeding:flats:flats_report&submit=Submit\">";
      echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
      echo 'onclick="return show_delete_warning();">';
      echo "</form></td>";
   }
   echo "</tr>";
}
echo "</table>";
if($crop != '%' && !$_SESSION['bigfarm']) {
     echo "<br clear=\"all\"/>";
     echo "<div class='pure-form pure-form-aligned'>";
     try {
        $totalResult = $dbcon->query($total);
     } catch (PDOException $p) {
        phpAlert("Error calculating seed total", $p);
     }
     while($row5 = $totalResult->fetch(PDO::FETCH_ASSOC)){
        echo '<div class="pure-control-group">';
        echo '<label for="totals"> Total Number of Seeds Planted:</label>';
   echo ' <input type="textbox" name="total" id="totals" class="textbox2" disabled value='.$row5['totalSum'].'>';
        echo '</div>';
     }
     // echo "<br clear=\"all\"/>";
     try {
        $totalResult = $dbcon->query($totalf);
     } catch (PDOException $p) {
        phpAlert('Error calculating tray total', $p);
     }
     while($row5 = $totalResult->fetch(PDO::FETCH_ASSOC)){
        echo '<div class="pure-control-group">';
        echo '<label for="totalt"> Total Number of Trays Planted:</label>';
   echo ' <input type="textbox" name="total" id="totalt" class="textbox2" disabled value='.$row5['totalFlats'].'>';
        echo '</div>';
     }
     echo '</div>';
}
        echo "<br clear=\"all\"/>";
        if($crop != "%") {
        echo "<br clear=\"all\"/>";
        }
   echo "<div class='pure-g'>";
   echo "<div class='pure-u-1-2'>";
   echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
   echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo '</form>';
   echo "</div>";
   echo "<div class='pure-u-1-2'>";
echo '<form method="POST" action = "/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
   echo "</div>";
   echo "</div>";

?>
