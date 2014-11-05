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
   if(!empty($_POST['fieldID'])) {
      $sql = "Select tractorName, fieldID, tilldate, tool, num_passes, comment, minutes,  percent_filled from tillage where tilldate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and fieldID like '".$fieldID."'  order by tilldate";
      echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
      $sqldata = mysql_query($sql) or die(mysql_error());
      echo "<table>";
      if( $fieldID == "%") {
         echo "<caption> Tillage Report for All Fields </caption>";
      } else {
         echo "<caption> Tillage Report for Field: ".$_POST['fieldID']."  </caption>";
      } 
      echo "<tr><th>Tractor</th><th>Field ID</th><th>Tillage Date</th><th>Implement</th><th>Number of Passes</th><th>Comments</th><th>Minutes</th><th> Percent Tilled </th></tr>";
      while($row = mysql_fetch_array($sqldata)) {
	echo "<tr><td>";
	echo $row['tractorName'];
	echo "</td><td>";
	echo $row['fieldID'];
	if(!$_SESSION['mobile']) {
           echo "</td><td style='width: 180px;'>";
        }
        //echo str_replace("-","/",$row['tilldate']);
	echo $row['tilldate'];       
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
	echo "</td><tr>";
	echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   $total="Select sum(num_passes) as total, sum(minutes) as total2, avg(num_passes) as average, avg(minutes) as average2 from tillage where tilldate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."'" ;
   $result=mysql_query($total) or die(mysql_error());
   while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total Number of Passes:&nbsp;</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total'].">";
        echo '<br clear="all"/>';
        echo "<label for='total'>Total Minutes:&nbsp;</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total2'].">";
        echo '<br clear="all"/>';
	$row3Deci3=number_format((float)$row1['average'], 1, '.', '');
        echo "<label for='total'>Average Number of Passes:&nbsp</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        echo '<br clear="all"/>';
	$row3Deci4=number_format((float)$row1['average2'], 1, '.', '');
        echo "<label for='total'>Average Minutes:&nbsp</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci4.">";
        echo '<br clear="all"/>';
  }
  echo '<br clear="all"/>';
  echo '<br clear="all"/>';
  echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';

echo "</form>";
echo '<form method="POST" action = "tillageReport.php?tab=soil:soil_fert:soil_till:till_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
}
}

?>
</div>
</body>
</html>
