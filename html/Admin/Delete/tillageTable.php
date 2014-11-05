<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM tillage WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   //if(!empty($fieldID)) {
      $sql = "Select id,tractorName, fieldID, tilldate, tool, num_passes, comment, minutes, percent_filled from tillage where tilldate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and fieldID like '".$fieldID."'  order by tilldate";
      $sqldata = mysql_query($sql) or die(mysql_error());
      //echo $sql;
      echo "<table>";
      if( $_GET['fieldID'] == "%") {
         echo "<caption> Tillage Report for All Fields </caption>";
      } else if ( $fieldID != "%") {
         echo "<caption> Tillage Report for Field: ".$_GET['fieldID']."  </caption>";
      } 
      echo "<tr><th>Tractor</th><th>Field ID</th><th>Tillage Date</th><th>Implement</th><th>Number of Passes</th><th>Comment</th><th>Minutes</th><th> Percent Tilled </th><th> Edit </th><th> Delete </th></tr>";
      while($row = mysql_fetch_array($sqldata)) {
	echo "<tr><td>";
	echo $row['tractorName'];
	echo "</td><td>";
	echo $row['fieldID'];
	if(!$_SESSION['mobile']) {
	   echo "</td><td style='width: 180px;'>";
	}
	echo $row['tilldate'];
//	echo $row['tilldate'];       
	 echo "</td><td>";
	echo $row['tool'];       
	echo "</td><td>";
	echo $row['num_passes'];
	echo "</td><td>";
	echo $row['comment'];
        echo "</td><td>";
	echo $row['minutes'];
	echo "</td><td>";
	echo $row['percent_filled']."%";
	echo "</td><td>";
	echo "<form method=\"POST\" action=\"tillageEdit.php?month=".$month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".$_GET['fieldID'].
           "&tab=admin:admin_delete:deletesoil:deletefert:deletetill&submit=Submit\">";
	echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form></td>";
	echo "<td><form method=\"POST\" action=\"tillageTable.php?month=".$month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".$_GET['fieldID'].
           "&tab=admin:admin_delete:deletesoil:deletefert:deletetill&submit=Submit\">";
	echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"></form></td>";
	echo "</tr>";
	echo "\n";
      }
      echo "</table>";
  // }
?>
