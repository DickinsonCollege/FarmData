<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
if(isset($_GET['id'])){
   $sqlDel="DELETE FROM transferred_to WHERE id=".$_GET['id'];
   mysql_query($sqlDel);
   echo mysql_error();
}
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$crop = escapehtml($_GET['transferredCrop']);
$genSel = $_GET['genSel'];
$fieldID = escapehtml($_GET['fieldID']);
$sql="Select id, username, transferred_to.fieldID,crop,seedDate,bedft,rowsBed,bedft * rowsBed as rowft,".
  " transdate,datediff(transdate,seedDate) as diffdate,flats, gen, hours, comments, bedft/length as beds".
  " from  transferred_to, field_GH where transferred_to.fieldID = field_GH.fieldID and".
  " crop like '".$crop."' and transferred_to.fieldID like '".
  $fieldID."' and gen like '".$genSel."' and transdate between '".$year."-".$month."-".$day.
  "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' order by transdate";

if ($crop != "%") {
   $sql2="select avg(diffdate) from (select datediff(transdate,seedDate) as ".
      "diffdate from transferred_to where crop = '".$crop."' and fieldID ".
      "like '".$fieldID."' and gen like '".$genSel."' and transdate between '".$year."-".$month."-".
      $day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."') as temp";
   $avg=mysql_query($sql2);
   echo mysql_error();
   $total = "select sum(bedft*rowsBed) as totalSum from transferred_to where ".
      "transdate between '".$year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-".$tcurDay."' AND crop = '".$crop."'".
      " and fieldID like '".$fieldID."' and gen like '".$genSel."'";
   $totalResult = mysql_query($total);
   echo mysql_error();
   $btotal = "select sum(bedft) as totalSum from transferred_to where ".
      "transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-".$tcurDay."' AND crop = '".$crop."'".
      " and fieldID like '".$fieldID."' and gen like '".$genSel."'";
   $btotalResult = mysql_query($btotal);
   echo mysql_error();
}

$result=mysql_query($sql);
echo mysql_error();
echo "<center>";
echo "<h2>Transplant Report for ";
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
echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Crop<center></th><th>Field</th><th>Date of Tray Seeding</th><th><center>Date of Transplanting</center></th><th><center>Days in Tray</center> </th>";
   if ($_SESSION['bedft']) {  
      echo "<th>Bed Feet</th>";
   }
   else {
      echo "<th>Beds</th>";
   }
   echo "<th>Rows/Bed</th><th><center>Row Feet</center></th><th>Trays</th>";
if ($_SESSION['gens']) {
   echo "<th>Succ&nbsp;#</th>";
}
if ($_SESSION['labor']) {
   echo "<th>Hours</th>";
}
echo "<th><center> Comments</center></th>";
if ($_SESSION['admin']) {
   echo "<th>User</th><th>Edit</th><th>Delete</th>";
}
echo "</tr></thead>";
   while ($row= mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['crop'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        //echo str_replace("-","/",$row['seedDate']);
        if ($row['seedDate'] == '0000-00-00') {
           echo "N/A";
        } else {
      echo $row['seedDate'];
        }
        echo "</td><td>";
   //echo str_replace("-","/",$row['transdate']);
        echo $row['transdate'];
        echo "</td><td>";
        echo $row['diffdate'];
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
   // echo $row['rowft'];
        echo number_format((float) $row['rowft'], 1, '.', '');
        echo "</td><td>";
   echo $row['flats'];
        echo "</td><td>";
   if ($_SESSION['gens']) {
        echo $row['gen'];
   echo "</td><td>";
   }
   if ($_SESSION['labor']) {
     echo number_format((float) $row['hours'], 2, '.', '');
     echo "</td><td>";
   }
   echo $row['comments'];
   if ($_SESSION['admin']) {
      echo "<td>".$row['username']."</td>";
      echo "<td><form method='POST' action=\"transEdit.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&transferredCrop=".encodeURIComponent($crop).
         "&genSel=".$genSel."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:transplant:transplant_report&submit=Submit\">";
      echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";
      echo "<td><form method='POST' action=\"transTable.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&transferredCrop=".encodeURIComponent($crop).
         "&genSel=".$genSel."&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=seeding:transplant:transplant_report&submit=Submit\">";
      echo "<input type='submit' class='deletebutton pure-button wide' value='Delete' onclick='return warn_delete();'></form></td>";
   }
   echo "</td></tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($crop != "%") {
   echo '<div class="pure-form pure-form-aligned">';
   while ($row2 = mysql_fetch_array($avg)) {
        $formatNum=number_format($row2['avg(diffdate)'],2,'.','');
   echo "<div class='pure-control-group'>";
   echo "<label for='average'>Average Days in Tray:</label> <input class='textbox2' type ='text' name='avgDays' disabled value=".$formatNum.">";
   echo "</div>";
   }
   while($row3 = mysql_fetch_array($btotalResult)) {
      echo "<div class='pure-control-group'>";
        echo "<label for='sum'>Total Number of Bed Feet Planted: </label> <input class='textbox3' type ='text' name='sum' disabled value=".$row3['totalSum'].">";
      echo "</div>";
   }
   while($row3 = mysql_fetch_array($totalResult)) {
      echo "<div class='pure-control-group'>";
        echo "<label for='sum'>Total Number of Row Feet Planted: </label> <input class='textbox3' type ='text' name='sum' disabled value=".$row3['totalSum'].">";
      echo "</div>";
   }
   echo "</div>";
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
}
if (!$result||(!$avg && $crop != "%")) {
        echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
}
echo "<div class='pure-g'>";
echo "<div class='pure-u-1-2'>";
echo "<form name='form' method='POST' action='/down.php'>";
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
echo '</form>';
echo '</div>';

echo "<div class='pure-u-1-2'>";
echo '<form method="POST" action = "/Seeding/transplantReport.php?tab=seeding:transplant:transplant_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo '</div>';
echo '</div>';
?>
