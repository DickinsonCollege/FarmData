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
   if (!empty($_POST['weed']) && !empty($_POST['fieldID'])) {
      $weed = escapehtml($_POST['weed']);
      $fieldID = escapehtml($_POST['fieldID']);
      $sql="Select sDate,fieldID,weed,infestLevel,goneToSeed,comments from weedScout where sDate between '". 
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and weed like '".$weed."' and fieldID like '".$fieldID.
         "' order by sDate";
   }
   $result=mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not Generate Weed Scouting Report: Please try again!\\n".mysql_error()."\");</script>\n";
   }
} else {
    echo  "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
}
echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
echo "<table >";
if ($weed=="%"){ 
   $var="All";
}else {
   $var=$_POST['weed'];
}
if ($fieldID=="%") {
   $var2="All";
}else {
   $var2=$_POST['fieldID'];
}
echo "<caption> Weed Scouting Records for Weed: ".$var." in Field: ".
   $var2."</caption>";

echo "<tr><th>Scout Date</th><th>Field</th><th>Species</th><th>Infestation</th><th>Seed</th><th>&nbsp;&nbsp;&nbsp;&nbsp;Comment</th></tr>";
while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        //echo str_replace("-","/",$row['sDate']);
	echo $row['sDate'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['weed'];
        echo "</td><td>";
        echo $row['infestLevel'];
        echo "</td><td>";
        echo $row['goneToSeed'];
	echo "</td><td>";
	echo $row['comments'];
        echo "</td></tr>";
}
echo "</table>";
echo '<br clear="all"/>';
        echo '<input type="submit" name="submit" class="submitbutton" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "weedReport.php?tab=soil:soil_scout:soil_weed:weed_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
