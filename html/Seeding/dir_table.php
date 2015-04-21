<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

if (isset($_GET['id'])) {
   $sqlDel="DELETE FROM dir_planted WHERE id=".$_GET['id'];
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
$fieldID = escapehtml($_GET['fieldID']);
$sql="select id, plantdate,dir_planted.crop,fieldID,bedft,rowsBed,bedft * rowsBed as rowft, hours, comments";
$sql .= ", gen, username from dir_planted where dir_planted.crop like '".$crop."' and ".
   "fieldID like '".$fieldID."' and gen like '".$genSel."' and plantdate between '".$year."-".
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
   $totalResult = mysql_query($total);
echo mysql_error();
   $btotalResult = mysql_query($btotal);
echo mysql_error();
}

$result=mysql_query($sql);
echo mysql_error();
echo "<table border>";
echo "<caption> Direct Seeding Report for ";
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
echo "</caption>";
echo "<tr><th>Date of Seeding</th><th>Crop</th>";
echo "<th>Field</th><th>Bed Feet</th><th>Rows/Bed</th><th>Row Feet</th>";
if ($_SESSION['labor']) {
   echo "<th>Hours</th>";
}
if ($_SESSION['gens']) {
   echo "<th>Succ&nbsp;#</th>";
}
echo "<th> Comments </th>";
if ($_SESSION['admin']) {
  echo "<th>User</th><th>Edit</th><th>Delete</th>";
}
echo "</tr>";
while ( $row = mysql_fetch_array($result)) {
   echo "<tr><td>";
   echo  $row['plantdate'];
   echo "</td><td>";
   echo $row['crop'];
   echo "</td><td>";
   echo $row['fieldID'];
   echo "</td><td>";
   echo number_format((float) $row['bedft'], 1, '.', '');
   echo "</td><td>";
   echo $row['rowsBed'];
   echo "</td><td>";
   echo number_format((float) $row['rowft'], 1, '.', '');
   echo "</td><td>";
    if ($_SESSION['labor']) {
        echo number_format((float) $row['hours'], 2, '.', '');
        echo "</td><td>";
    }
   if ($_SESSION['gens']) {
      echo $row['gen']."</td><td>";
   }
   echo $row['comments'];
   echo "</td>";
   if ($_SESSION['admin']) {
      echo "<td>".$row['username']."</td>";
      echo "<td><form method=\"POST\" action=\"dirEdit.php?month=".
         $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
         "&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
         "&genSel=".$_GET['genSel']."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:direct:direct_report&submit=Submit\">";
      echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"";
      echo 'onclick="return show_warning();">';
      echo "</form> </td>";

      echo "<td><form method=\"POST\" action=\"dir_table.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth.  "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop=".
          encodeURIComponent($_GET['crop']).
         "&genSel=".$_GET['genSel']."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:direct:direct_report&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"";
      echo 'onclick="return show_delete_warning();">';
      echo "</form> </td>";
   }
   echo "</tr>";
}
echo "</table>";
if($crop != '%') {
        echo '<br clear="all"/>';
   while($row5 = mysql_fetch_array($btotalResult)){
      echo '<label for="total"> Total Bed Feet Planted: &nbsp;</label> '.
              '<input disabled type="textbox" name="total" style="width: '.
              '100px;" id="total" class="textbox2" value='
              .number_format((float) $row5['totalSum'], 1, '.', '').'>';
   }
        echo '<br clear="all"/>';
   while($row5 = mysql_fetch_array($totalResult)){
      echo '<label for="total"> Total Row Feet Planted: &nbsp;</label>'.
             ' <input disabled type="textbox" name="total" style="width: '.
             '100px;" id="total" class="textbox2" value='.
             number_format((float) $row5['totalSum'], 1, '.', '').'>';
   }
        echo '<br clear="all"/>';
}
        echo '<br clear="all"/>';
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
 ?>
<input class = "submitbutton" type="submit" name="submit" value="Download Report">
<?php
echo '</form>';
echo '<form method="GET" action = "plantReport.php">';
echo '<input type="hidden" name="tab" value="seeding:direct:direct_report">';
echo '<input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
