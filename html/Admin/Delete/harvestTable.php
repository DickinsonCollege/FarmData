<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM harvested WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $crop = escapehtml($_GET['crop']);

/*
   $sqlget = "SELECT id,harvested.crop, username,hardate,fieldID, yield, ".
      "units, harvested.hours, harvested.comments FROM harvested, plant where hardate BETWEEN '".$year."-".$month.
      "-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' and harvested.crop like '" .$crop."' and plant.crop = harvested.crop order by hardate";
*/
   $sqlget = "SELECT id,crop, username,hardate,fieldID, yield, unit, hours, comments ".
      "FROM harvested where hardate BETWEEN '".$year."-".$month.
      "-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' and crop like '" .$crop."' order by hardate";
   $sqldata = mysql_query($sqlget) or die("ERROR");
   echo "<table border>";
   $crop = $_GET['crop'];
   if ($crop == "%") {
      $crop = "All Crops";
   }
   echo "<caption> Harvest Report for ".$crop." </caption>";
   echo "<tr><th>Harvest Date</th><th>Crop</th><th>Username</th>".
        "<th>FieldID</th><th>Yield</th><th>Unit</th>";
   if ($_SESSION['labor']) {
      echo "<th>Hours</th>";
   }
   echo "<th>Comments</th>".
	"<th>Edit</th><th>Delete</th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['hardate'];
      echo "</td><td>";
      echo $row['crop'];
      echo "</td><td>";
      echo $row['username'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo number_format((float) $row['yield'], 2, '.', '');
      echo "</td><td>";
      echo $row['unit'];
      echo "</td><td>";
      if ($_SESSION['labor']) {
         echo number_format((float) $row['hours'], 2, '.', '');
         echo "</td><td>";
      }
      echo $row['comments'];
      echo "</td>";
      
      echo "<td><form method=\"POST\" action=\"harvestEdit.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
      "&crop=".encodeURIComponent($_GET['crop']).
      "&tab=admin:admin_delete:deleteharvest&submit=Submit\">";
      echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form> </td>";
      
      echo "<td><form method=\"POST\" action=\"harvestTable.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
      "&tab=admin:admin_delete:deleteharvest&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"></form></td>";
      echo "</tr>";
   }
   echo "</table>";
?>
