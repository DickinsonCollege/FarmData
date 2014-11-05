<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];$tcurYear = $_POST['tyear'];
$tcurMonth = $_POST['tmonth'];
$tcurDay = $_POST['tday'];
$crop = escapehtml($_POST['transferredCrop']);
$fieldID = escapehtml($_POST['fieldID']);
$sql="Select fieldID,crop,seedDate,bedft,rowsBed,bedft * rowsBed as rowft,".
  " transdate,datediff(transdate,seedDate) as diffdate,flats, hours, comments ".
  " from  transferred_to where crop like '".$crop."' and fieldID like '".
  $fieldID."' and transdate between '".$year."-".$month."-".$day.
  "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' order by transdate";
if ($crop != "%") {
   $sql2="select avg(diffdate) from (select datediff(transdate,seedDate) as ".
      "diffdate from transferred_to where crop = '".$crop."' and fieldID ".
      "like '".$fieldID."' and transdate between '".$year."-".$month."-".
      $day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."') as temp";
   $avg=mysql_query($sql2);
   echo mysql_error();
   $total = "select sum(bedft*rowsBed) as totalSum from transferred_to where ".
      "transdate between '".$year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-".$tcurDay."' AND crop = '".$crop."'".
      " and fieldID like '".$fieldID."'";
   $totalResult = mysql_query($total);
   echo mysql_error();
   $btotal = "select sum(bedft) as totalSum from transferred_to where ".
      "transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-".$tcurDay."' AND crop = '".$crop."'".
      " and fieldID like '".$fieldID."'";
   $btotalResult = mysql_query($btotal);
   echo mysql_error();
}

/*
   if ($_POST['transferredCrop']=="All") {
      $sql="Select fieldID,crop,seedDate,bedft, rowsBed, bedft * rowsBed as rowft, transdate,datediff(transdate,seedDate) as diffdate,flats, hours, comments from  transferred_to where  transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' order by transdate";

   }else {

      $sql="Select fieldID,crop,seedDate,bedft,rowsBed,bedft * rowsBed as rowft, transdate,datediff(transdate,seedDate) as diffdate,flats, hours, comments from  transferred_to where crop='".$_POST['transferredCrop']."' and transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' order by transdate";
      $sql2="select avg(diffdate)  from (Select datediff(transdate,seedDate) as diffdate from transferred_to where crop='".$_POST["transferredCrop"]."' and transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."') as temp";
      $avg=mysql_query($sql2);
      $total = "select sum(bedft*rowsBed) as totalSum from transferred_to where transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' AND crop ='".$_POST['transferredCrop']."'" ;
      $totalResult = mysql_query($total);
      $btotal = "select sum(bedft) as totalSum from transferred_to where transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' AND crop ='".$_POST['transferredCrop']."'" ;
      $btotalResult = mysql_query($btotal);
   }
*/
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
$result=mysql_query($sql);
echo mysql_error();
echo "<table>";
echo "<caption>Transplant Report for ";
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
echo "</caption>";
   echo "<tr><th>Crop<center></th><th>Field</th><th>SeedDate</th><th><center>TransDate</center></th><th><center>DaysinFlat</center> </th><th>Bed Feet</th><th>Rows/Bed</th><th><center>Row Feet</center></th><th>Flats</th>";
if ($_SESSION['labor']) {
   echo "<th>Hours</th>";
}
echo "<th><center> Comments</center></th></tr>";
   while ($row= mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['crop'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        //echo str_replace("-","/",$row['seedDate']);
	echo $row['seedDate'];
        echo "</td><td>";
	//echo str_replace("-","/",$row['transdate']);
        echo $row['transdate'];
        echo "</td><td>";
        echo $row['diffdate'];
        echo "</td><td>";
        echo number_format((float) $row['bedft'], 1, '.', '');
	// echo $row['bedft'];
        echo "</td><td>";
	echo $row['rowsBed'];
        echo "</td><td>";
	// echo $row['rowft'];
        echo number_format((float) $row['rowft'], 1, '.', '');
        echo "</td><td>";
	echo $row['flats'];
        echo "</td><td>";
   if ($_SESSION['labor']) {
        echo number_format((float) $row['hours'], 2, '.', '');
	echo "</td><td>";
   }
        echo $row['comments'];
        echo "</td></tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($crop != "%") {
   while ($row2 = mysql_fetch_array($avg)) {
        $formatNum=number_format($row2['avg(diffdate)'],2,'.','');
	echo "<label for='average'>Average Days in Flat: &nbsp</label> <input style='width: 100px;' class='textbox2'type ='text' name='avgDays' disabled value=".$formatNum.">";
   }
   echo '<br clear="all"/>';
   while($row3 = mysql_fetch_array($btotalResult)) {
        echo "<label for='sum'>Total Bed Feet Planted: &nbsp;</label> <input class='textbox3'type ='text' name='sum' disabled value=".$row3['totalSum'].">";
   }
   echo '<br clear="all"/>';
   while($row3 = mysql_fetch_array($totalResult)) {
        echo "<label for='sum'>Total Row Feet Planted: &nbsp;</label> <input class='textbox3'type ='text' name='sum' disabled value=".$row3['totalSum'].">";
   }
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
}
echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';

if (!$result||(!$avg && $crop != "%")) {
        echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
}
echo '</form>';
echo '<form method="POST" action = "/Seeding/transplantReport.php?tab=seeding:transplant:transplant_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
