<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
 include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
if(isset($_POST['submit'])) {
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $fieldID = escapehtml($_POST['fieldID']);
   $cgroup = escapehtml($_POST['cgroup']);
   $stage = escapehtml($_POST['stage']);
   $disease = escapehtml($_POST['disease']);
   $sql="Select sDate,fieldID,cropGroup,disease,infest,stage,comments from diseaseScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' and cropGroup like '".
      $cgroup."' and stage like '".$stage."' and disease like '"
      .$disease."'";
   $result=mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not Generate Disease Scouting Report: Please try again!\\n".mysql_error()."\");</script>\n";
   }
   echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
   echo "<table border>";
   if ($disease=="%"){
      $var="All";
   }else {
      $var=$_POST['disease'];
   }
   if ($fieldID=="%") {
      $var2="All";
   }else {
      $var2=$_POST['fieldID'];
   }
   if ($cgroup=="%") {
      $var3="All";
   }else {
      $var3=$_POST['cgroup'];
   }
   if ($stage=="%") {
      $var4="Any";
   }else {
      $var4=$_POST['stage'];
   }
   echo "<caption> Disease Scouting Records in Field: ".$var." for Crop Group in: ".$var3." Disease: ".$var." at Stage: ".$var4."</caption>";

   echo "<tr><th>Scout Date</th><th>Field ID</th><th>Crop Group</th><th>Disease Species</th><th>Infestation Level</th><th>Crop Stage</th><th>Comments</th></tr>";
   while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        //echo str_replace("-","/",$row['sDate']);
	echo $row['sDate'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['cropGroup'];
        echo "</td><td>";
        echo $row['disease'];
        echo "</td><td>";
        echo $row['infest'];
        echo "</td><td>";
        echo $row['stage'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td></tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
   echo "</form>";
   echo '<form method="POST" action = "diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
}
?>
