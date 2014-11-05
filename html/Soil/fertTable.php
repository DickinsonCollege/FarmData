<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
if(!empty($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $fieldID = escapehtml($_POST['fieldID']);
   $group = escapehtml($_POST['group']);
   $material = escapehtml($_POST['material']);
   $sql = "select inputDate, fieldID, fertilizer, cropGroup, rate, numBeds, totalApply, comments ".
      "from fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and cropGroup like '".
      $group."' and fertilizer like '".$material."' order by inputDate";
      echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_POST['fieldID'];
   } 
   if( $group == "%") {
      $grp = "All Crop Groups";
   } else {
      $grp = $_POST['group'];
   } 
   if( $material == "%") {
      $mat = "All Materials";
   } else {
      $mat = $_POST['material'];
   } 
   echo "<caption> Dry Fertilizer Application Report for ".$mat." on ".$grp." in Field: ".$fld."  </caption>";
   
   echo "<tr><th>Date</th><th>Field</th><th>Material</th><th>Crop Group</th><th>Application Rate<br>".
     "(lbs/acre)</th><th>Number of Beds</th><th>Total Material Applied</th><th>Comments</th></tr>";
   while ($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['inputDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['fertilizer'];       
      echo "</td><td>";
      echo $row['cropGroup'];
      echo "</td><td>";
      echo $row['rate'];
      echo "</td><td>";
      echo $row['numBeds'];
      echo "</td><td>";
      echo $row['totalApply'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td><tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($material != "%") {
      $total="Select sum(totalApply) as total from fertilizer where inputDate between '".$year."-".$month.
         "-".$day."' AND '".$tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID.
         "' and cropGroup like '".  $group."' and fertilizer like '".$material."'";

      $result=mysql_query($total) or die(mysql_error());
      while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total ".$material." Applied:&nbsp;</label>";
	echo "<input disabled class='textbox2' style='width: 120px;' type ='text' value=".
          number_format((float)$row1['total'], 2, '.', '').">";
        echo "&nbsp; POUNDS";
        echo '<br clear="all"/>';
     }
     echo '<br clear="all"/>';
  }

  echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';

echo "</form>";
echo '<form method="POST" action = "fertReport.php?tab=soil:soil_fert:soil_fertilizer:fertilizer_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
}

?>
</div>
