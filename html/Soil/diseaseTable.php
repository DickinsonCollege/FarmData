<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM diseaseScout WHERE id=".$_GET['id'];
      mysql_query($sqlDel) or die(mysql_error());
      echo mysql_error();
   }

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $crop = escapehtml($_GET['crop']);
   $stage = escapehtml($_GET['stage']);
   $disease = escapehtml($_GET['disease']);
   $sql="Select id, sDate,fieldID,crops,disease,infest,stage,comments from diseaseScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' and stage like '".$stage."' and disease like '"
      .$disease."' and crops like '%".$crop."%'";
   $result=mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not Generate Disease Scouting Report: Please try again!\\n".mysql_error()."\");</script>\n";
   }
   echo "<table border>";
   if ($disease=="%"){
      $var="All";
   }else {
      $var=$_GET['disease'];
   }
   if ($fieldID=="%") {
      $var2="All";
   }else {
      $var2=$_GET['fieldID'];
   }
   if ($crop=="%") {
      $var3="All";
   }else {
      $var3=$_GET['crop'];
   }
   if ($stage=="%") {
      $var4="Any";
   }else {
      $var4=$_GET['stage'];
   }
   echo "<caption> Disease Scouting Records in Field: ".$var." for Crop: ".$var3." Disease: ".$var." at Stage: ".$var4."</caption>";

   echo "<tr><th>Scout Date</th><th>Field ID</th><th>Crops</th><th>Disease Species</th><th>Infestation Level</th><th>Crop Stage</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr>";
   while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        //echo str_replace("-","/",$row['sDate']);
	echo $row['sDate'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['crops'];
        echo "</td><td>";
        echo $row['disease'];
        echo "</td><td>";
        echo $row['infest'];
        echo "</td><td>";
        echo $row['stage'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td>";
        if ($_SESSION['admin']) {
           echo "<td><form method=\"POST\" action=\"diseaseEdit.php?month=".
              $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&disease=".encodeURIComponent($_GET['disease']).
              "&stage=".encodeURIComponent($_GET['stage']).
              "&tab=soil:soil_scout:soil_disease:disease_report\">";
           echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton\" value=\"Edit\"></form></td>";

           echo "<td><form method=\"POST\" action=\"diseaseTable.php?month=".
              $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
              "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&crop=".encodeURIComponent($_GET['crop']).
              "&disease=".encodeURIComponent($_GET['disease']).
              "&stage=".encodeURIComponent($_GET['stage']).
              "&tab=soil:soil_scout:soil_disease:disease_report\">";
           echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton\"";
           echo "onclick='return warn_delete();' value=\"Delete\"></form></td>";

        }
        echo "</tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   echo "<form name='form' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
   echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
   echo "</form>";
   echo '<form method="POST" action = "diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
