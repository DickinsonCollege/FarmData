<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
$farm = $_SESSION['db'];
?>

<form name='form' method='POST' action='/down.php'>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$tcurYear = $_POST['tyear'];
$tcurMonth = $_POST['tmonth'];
$tcurDay = $_POST['tday'];
$genSel = $_POST['genSel'];
$crop = escapehtml($_POST['crop']);
$fieldID = escapehtml($_POST['fieldID']);
$sql="select plantdate,dir_planted.crop,fieldID,bedft,rowsBed,bedft * rowsBed as rowft, hours, comments";
$sql .= ", gen from dir_planted where dir_planted.crop like '".$crop."' and ".
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
echo "<tr><th>Plant Date</th><th>Crop</th>";
echo "<th>Field</th><th>Bed Feet</th><th>Rows/Bed</th><th>Row Feet</th>";
if ($_SESSION['labor']) {
   echo "<th>Hours</th>";
}
if ($_SESSION['gens']) {
   echo "<th>Succ&nbsp;#</th>";
}
echo "<th> Comments </th></tr>";
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
   echo "</td></tr>";
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
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
 ?>
<input class = "submitbutton" type="submit" name="submit" value="Download Report">
<?php
echo '</form>';
echo '<form method="GET" action = "plantReport.php">';
echo '<input type="hidden" name="tab" value="seeding:direct:direct_report">';
echo '<input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
