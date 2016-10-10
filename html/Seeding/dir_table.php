<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

if (isset($_GET['id'])) {
   $sqlDel="DELETE FROM dir_planted WHERE id=".$_GET['id'];
   try {
      $res = $dbcon->prepare($sqlDel);
      $res->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
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
$fieldID = escapehtml($_GET['fieldID']);

$sql="select id, plantdate,dir_planted.crop,dir_planted.fieldID,bedft,rowsBed,bedft * rowsBed as rowft, ".
   "bedft / length as beds, hours, comments, gen, username, annual, lastHarvest ".
   "from dir_planted, field_GH ".
   "where dir_planted.fieldID = field_GH.fieldID and dir_planted.crop like '".$crop."' and ".
   "dir_planted.fieldID like '".$fieldID."' and gen like '".$genSel."' and plantdate between '".$year."-".
   $month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' order by plantdate ";
if ($crop != "%") {
   $total = "select sum(bedft * rowsBed) as totalSum from dir_planted where ".
     "plantdate between '".$year."-".$month."-".$day."' AND '".
     $tcurYear."-".$tcurMonth."-".$tcurDay."' AND crop ='".$crop."' ".
     "and fieldID like '".$fieldID."' and gen like '".$genSel."'";
   $btotal = "select sum(bedft) as totalSum from dir_planted where plantdate ".
     "between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".
     $tcurMonth."-".$tcurDay."' AND crop ='".$crop."' and fieldID like '".
     $fieldID."' and gen like '".$genSel."'";
   try {
      $totalResult = $dbcon->query($total);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
   $acretotal = "select sum(bedft * size / (length * numberOfBeds)) as acreft ".
     " from dir_planted, field_GH ".
     " where dir_planted.fieldID = field_GH.fieldID and plantdate ".
     "between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".
     $tcurMonth."-".$tcurDay."' AND crop ='".$crop."' and field_GH.fieldID like '".
     $fieldID."' and gen like '".$genSel."'";
   try {
      $acreTotal = $dbcon->query($acretotal);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
   try {
      $btotalResult = $dbcon->query($btotal);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
}

try {
   $result=$dbcon->query($sql);
} catch (PDOException $p) {
   phpAlert('', $p);
}
echo "<table class='pure-table pure-table-bordered'>";
echo "<center>";
echo "<h2> Direct Seeding Report for ";
if ($crop == "%") {
  echo "All Crops in ";
} else {
  echo $crop." in ";
}
if ($fieldID == "%") {
  echo "All Fields";
} else {
  echo "Field ".$fieldID;
}
if ($_SESSION['gens']) {
   if ($genSel == "%") {
      echo " of All Successions";
   } else {
      echo " of Succession ".$genSel;
   }
}
echo "</h2>";
echo "</center>";
echo "<thead><tr><th>Date of Seeding</th><th>Crop</th>";
echo "<th>Field</th>";
if ($_SESSION['bedft']) {
echo "<th>Bed Feet</th>";
}
else {
echo "<th>Beds</th>";
}

echo "<th>Rows/Bed</th><th>Row Feet</th>";
if ($_SESSION['labor']) {
   echo "<th>Hours</th>";
}
if ($_SESSION['gens']) {
   echo "<th>Succ&nbsp;#</th>";
}
echo "<th> Annual </th>";
echo "<th> Last Harvest Date</th>";
echo "<th> Comments </th>";
if ($_SESSION['admin']) {
  echo "<th>User</th><th>Edit</th><th>Delete</th>";
}
echo "</tr></thead>";
while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<tr><td>";
   echo  $row['plantdate'];
   echo "</td><td>";
   echo $row['crop'];
   echo "</td><td>";
   echo $row['fieldID'];
   echo "</td><td>";
   if ($_SESSION['bedft']) {
      echo number_format((float) $row['bedft'], 1, '.', '');
   }
   else {
      echo number_format((float) $row['beds'], 2, '.', '');
   }
   echo "</td><td>";
   echo $row['rowsBed'];
   echo "</td><td>";
   echo number_format((float) $row['rowft'], 1, '.', '');
   echo "</td><td>";
    if ($_SESSION['labor']) {
        echo number_format((float) $row['hours'], 2, '.', '');
        echo "</td>";
    }
   if ($_SESSION['gens']) {
      echo "<td>";
      echo $row['gen']."</td>";
   }
   if ($row['annual'] == 1) {
      echo "<td>Yes</td>";
      echo "<td>&nbsp;</td>";
   } else {
      echo "<td>No</td>";
      echo "<td>".$row['lastHarvest']."</td>";
   }
   echo "<td>";
   echo $row['comments'];
   echo "</td>";
   if ($_SESSION['admin']) {
      echo "<td>".$row['username']."</td>";
      echo "<td><form method=\"POST\" action=\"dirEdit.php?month=".
         $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
         "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
         "&genSel=".$_GET['genSel']."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:direct:direct_report&submit=Submit\">";
      echo "<input type=\"submit\" class=\"editbutton wide pure-button\" value=\"Edit\"";
      echo 'onclick="return show_warning();">';
      echo "</form> </td>";

      echo "<td><form method=\"POST\" action=\"dir_table.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth.  "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop=".
          encodeURIComponent($_GET['crop']).
         "&genSel=".$_GET['genSel']."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:direct:direct_report&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton wide pure-button\" value=\"Delete\"";
      echo 'onclick="return show_delete_warning();">';
      echo "</form> </td>";
   }
   echo "</tr>";
}
echo "</table>";
if($crop != '%') {
        echo '<br clear="all"/>';
   echo "<div class='pure-form pure-form-aligned'>";
   while($row5 = $btotalResult->fetch(PDO::FETCH_ASSOC)){
      echo '<div class="pure-control-group">';
      echo '<label for="total"> Total Bed Feet Planted:</label> '.
              '<input disabled type="textbox" name="total" id="total" class="textbox2" value='
              .number_format((float) $row5['totalSum'], 1, '.', '').'>';
      echo "</div>";
   }
   while($row5 = $totalResult->fetch(PDO::FETCH_ASSOC)){
      echo '<div class="pure-control-group">';
      echo '<label for="total"> Total Row Feet Planted:</label>'.
             ' <input disabled type="textbox" name="total" id="total" class="textbox2" value='.
             number_format((float) $row5['totalSum'], 1, '.', '').'>';
      echo "</div>";
   }
   while($row6 = $acreTotal->fetch(PDO::FETCH_ASSOC)){
      echo '<div class="pure-control-group">';
      echo '<label for="total"> Total Acres Planted:</label>'.
             ' <input disabled type="textbox" name="total" id="total" class="textbox2" value='.
             number_format((float) $row6['acreft'], 3, '.', '').'>';
      echo "</div>";
   }
        echo '</div>';
}
        echo '<br clear="all"/>';
echo '<div class="pure-g">';
echo '<div class="pure-u-1-2">';
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
 ?>
<input class = "submitbutton wide pure-button" type="submit" name="submit" value="Download Report">
<?php
echo '</form></div>';
echo '<div class="pure-u-1-2">';
echo '<form method="GET" action = "plantReport.php">';
echo '<input type="hidden" name="tab" value="seeding:direct:direct_report">';
echo '<input type="submit" class="submitbutton wide pure-button" value = "Run Another Report"></form>';
echo '</div></div>';
?>
