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
   if(!empty($_POST['crop'])) {
      $year = $_POST['year'];
      $month = $_POST['month'];
      $day = $_POST['day'];
      $tcurYear = $_POST['tyear'];
      $tcurMonth = $_POST['tmonth'];
      $tcurDay = $_POST['tday'];
      $crop = escapehtml($_POST['crop']);
      $fieldID = escapehtml($_POST['fieldID']);
      $pest = escapehtml($_POST['pest']);
      $sql="select sDate,crop,fieldID,pest,avgCount,comments from pestScout where sDate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and crop like '".$crop."' and fieldID like '".
         $fieldID."' and pest like '".$pest."' order by sDate";
   }
   $result=mysql_query($sql);
   if(!$result){
    echo "<script>alert(\"Could not Generate Insect Scouting Report: Please try again!\\n".mysql_error()."\");</script>\n";
   }
   echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
   echo "<table border>";
   if ($crop=="%"){
      $var="All";
   }else {
      $var=$_POST['crop'];
   }
   if ($fieldID=="%") {
      $var2="All";
   }else {
      $var2=$_POST['fieldID'];
   }
   if ($pest=="%") {
      $var3="All";
   }else {
      $var3=$_POST['pest'];
   }
   echo "<caption> Insect Scouting Records for ".$var." in Field: ".$var2." Insect: ".$var3."</caption>";
echo "<tr><th>Scout Date</th><th>Crop</th><th>Field ID</th><th>Insect</th><th>Average Count</th><th>Comments</th></tr>";
      while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['sDate'];
        echo "</td><td>";
        echo $row['crop'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pest'];
        echo "</td><td>";
        echo $row['avgCount'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td></tr>";
      }
      echo "</table>";
   }
        echo '<br clear="all"/>';
        echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "pestReport.php?tab=soil:soil_scout:soil_pest:pest_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>

