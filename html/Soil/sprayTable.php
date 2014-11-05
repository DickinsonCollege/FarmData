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
   $sprayMaterial = escapehtml($_POST['sprayMaterial']);
   $fieldID = escapehtml($_POST['fieldID']);
   if(!empty($_POST['sprayMaterial']) && !empty($_POST['fieldID'])) {
      $sql = "Select sprayDate, fieldID, water, materialSprayed, rate, BRateUnits, totalMaterial, mixedWith, cropGroup, comments from bspray, tSprayMaterials where sprayDate between '".
        $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
        $fieldID."'and materialSprayed like '".$sprayMaterial.
        "' and materialSprayed = sprayMaterial order by sprayDate";
      $sqldata = mysql_query($sql) or die(mysql_error());
      echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
      echo "<table>";
      echo "<colgroup><col width='10px' id='col1'/>";
      echo "<col id='col2'/>";
      echo "<col id='col3'/>";
      echo "<col id='col4'/>";
      echo "<col id='col5'/>";
      echo "</colgroup>";
      if($sprayMaterial =="%" && $fieldID == "%") {
         echo "<caption> Backpack Spray Report for All Spray Materials on All Fields </caption>";
      } else if ($sprayMaterial != "%" && $fieldID == "%") {
         echo "<caption> Backpack Spray Report for ".$_POST['sprayMaterial']." on All Fields </caption>";
      } else{
         echo "<caption> Backpack Spray Report for ".$_POST['sprayMaterial']." on Field: ".$fieldID." </caption>";
      }
      echo "<tr><th>Spray Date</th><th>Field ID</th><th>Water (Gallons)</th><th>Material Sprayed</th><th>Rate</th><th>Total Material</th><th>Mixed With</th><th>Crop Group</th><th> Comments </th></tr>";
      while($row = mysql_fetch_array($sqldata)) {
	echo "<tr><td>";
	//echo str_replace("-","/",$row['sprayDate']);
	echo $row['sprayDate'];
	echo "</td><td>";
	echo $row['fieldID'];
	echo "</td><td>";
	echo $row['water'];       
	 echo "</td><td>";
	echo $row['materialSprayed'];
	echo "</td><td>";
	echo $row['rate']." ".$row['BRateUnits']."/gallon";
        echo "</td><td>";
	echo $row['totalMaterial'];
	echo "</td><td>";
	echo $row['mixedWith'];
	echo "</td><td>";
	echo $row['cropGroup'];
	echo "</td><td>";
	echo $row['comments'];
        echo "</td></tr>";
	echo "\n";
      }
      echo "</table>";
      echo '<br clear="all"/>';
      $total="Select sum(water) as water, sum(totalMaterial) as total from bspray where sprayDate between '".$year."-".$month."-".$day.
         "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and materialSprayed like '".$sprayMaterial."'" ;
      $result=mysql_query($total) or die(mysql_error());
      $other = "Select BRateUnits from tSprayMaterials where sprayMaterial like '".$sprayMaterial."'";
      $result2 = mysql_query($other) or die(mysql_error());
      $row2 = mysql_fetch_array($result2);
      while ($row1 = mysql_fetch_array($result) ) {
        echo "<label for='total'>Total Gallons of Water Used:&nbsp;</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['water'].">";
        if ($sprayMaterial != "%") {
           echo '<br clear="all"/>';
           echo "<label for='total'>Total Material Used:&nbsp;</label>";
	   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total'].">";
	   echo "<label style='margin-top: 4px'for='unit'>&nbsp;".$row2['BRateUnits']."(S)</label>";
        }
      }
      echo '<br clear="all"/>';
      echo '<br clear="all"/>';
      echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
      echo '</form>';
      echo '<form method="POST" action = "sprayReport.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
   }
}
?>
</div>
</body>
</html>
