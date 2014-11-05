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
   if(!empty($_POST['fieldID'])) {
      $year = $_POST['year'];
      $month = $_POST['month'];
      $day = $_POST['day'];
      $tcurYear = $_POST['tyear'];
      $tcurMonth = $_POST['tmonth'];
      $tcurDay = $_POST['tday'];
      $fieldID = escapehtml($_POST['fieldID']);
		$hiddensql = "SELECT killDate, (SELECT group_concat(coverCrop SEPARATOR '; ') FROM coverKill WHERE id=coverKill_master.id) as crops, 
			seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
			totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
			FROM coverKill_master 
			WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
			AND fieldID LIKE '".$fieldID."' 
			ORDER BY killDate";
		$sql = "SELECT killDate, seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
			totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
			FROM coverKill_master 
			WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
			AND fieldID LIKE '".$fieldID."' 
			ORDER BY killDate";
      $result=mysql_query($sql);
      if(!$result){
          echo "<script>alert(\"Could not retrieve data: Please try again!\\n".mysql_error()."\");</script>\n";
      }
   } else {
      echo  "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
   echo '<input type="hidden" value="'.escapehtml($hiddensql).'" name = "query" id="query">';
   echo "<table border>";
   if($fieldID != "%") {
      echo "<caption> Incorporation Records for: Field ".$_POST['fieldID']."</caption>";
   } else {
      echo "<caption> Incorporation Records for All Fields </caption>";
   }
   echo "<tr><th>Kill Date</th><th>Cover Crop</th><th>Seed Date</th><th> Field </th><th>Incorporation Tool</th><th>Total Biomass</th><th> Biomass Pounds Per Acre </th><th>Comments</th></tr>";
   while ( $row = mysql_fetch_array($result)) {
		$allCropsQuery = "SELECT coverCrop FROM coverKill WHERE id=".$row['id'];
		$cropResult = mysql_query($allCropsQuery);
		$cropString = "";
		$count = 1;
		while ($cropRow = mysql_fetch_array($cropResult)) {
			$cropString .= $cropRow['coverCrop'];
			if (mysql_num_rows($cropResult) > $count) {
				$cropString .= "<br/>";
			}
			$count++;
		}
        echo "<tr><td>";
        echo $row['killDate'];
        echo "</td><td>";
        echo $cropString;
        echo "</td><td>";
        echo $row['seedDate'];
        echo "</td><td>";
	     echo $row['fieldID'];
	     echo "</td><td>";
        echo $row['incorpTool'];
        echo "</td><td>";
        $row3Deci3=number_format((float)$row['totalBiomass'], 3, '.', '');
        echo $row3Deci3;
        echo "</td><td>";
        $var=number_format($row['bioPerAcre'], 2, '.', '');
        echo $var;
        echo "</td><td>";
        echo $row['comments'];
        echo "</td></tr>";
   }
   echo "</table>";
}

echo '<br clear="all"/>';
$sqlget = "Select sum(totalBiomass) as total, avg(totalBiomass) as average from coverKill_master where killDate between '"
    .$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
     $tcurDay."' and fieldID like '".$fieldID."'";
$result = mysql_query($sqlget);
while($row1 = mysql_fetch_array($result)) {
	echo "<label for='total'>Total Biomass:&nbsp;</label>";
	echo "<input class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$var=number_format($row1['total'],3,'.','').">";
        echo '<br clear="all"/>';
        $row3Deci3=number_format((float)$row1['average'], 3, '.', '');
        echo "<label for='total'>Average Biomass:&nbsp</label>";
        echo "<input class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
}
echo '<br clear="all"/>';
echo '<br clear="all"/>';
        echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';

echo "</form>";
echo '<form method="POST" action = "incorpReport.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>


