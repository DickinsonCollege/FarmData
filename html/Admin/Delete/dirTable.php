<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
   if(isset($_GET['id'])){
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
   $crop = escapehtml($_GET['crop']);

   $sqlget = "SELECT id,crop,username,plantdate,fieldID,bedft,rowsBed,hours,comments".
      " FROM dir_planted where plantdate BETWEEN '".$year."-".$month."-".
      $day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' and crop like '" .$crop."' order by plantdate";
   $sqldata = mysql_query($sqlget) or die("ERROR");
   echo "<table border>";
   $crop = $_GET['crop'];
   if ($crop == "%") {
      $crop = "All Crops";
   }
   echo "<caption> Direct Seeding Report for ".$crop." </caption>";
   echo "<tr><th>Plant Date</th><th>Crop</th><th>Username</th><th>fieldID</th><th>Bed Feet</th><th>Rows/Bed</th>";
   if ($_SESSION['labor']) {
	echo "<th>Hours</th>";
   }
   echo "<th>Comments</th><th>Edit</th><th>Delete</th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['plantdate'];
      echo "</td><td>";
      echo $row['crop'];
      echo "</td><td>";
      echo $row['username'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['bedft'];
      echo "</td><td>";
      echo $row['rowsBed'];
      echo "</td><td>";
      if ($_SESSION['labor']) {
         echo $row['hours'];
         echo "</td><td>";
      }
      echo $row['comments'];
     
      echo "<td><form method=\"POST\" action=\"dirEdit.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth.  "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
      "&tab=admin:admin_delete:deleteseed:deletedirplant&submit=Submit\">";
      echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form> </td>";

      echo "<td><form method=\"POST\" action=\"dirTable.php?month=".$month."&day=".$day."&year=".$year.
      "&tmonth=".$tcurMonth.  "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&crop=".encodeURIComponent($_GET['crop']).
      "&tab=admin:admin_delete:deleteseed:deletedirplant&submit=Submit\">";
      echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"></form> </td>";
      echo "</tr>";
   }
   echo "</table>";
?>
