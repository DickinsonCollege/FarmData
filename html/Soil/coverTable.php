<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
if(isset($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   //$crop1 = escapehtml($_POST['crop1']);
   //$crop2 = escapehtml($_POST['crop2']);
   $fieldID = escapehtml($_POST['fieldID']);
   $sql = "SELECT id, seed_method,fieldID, ((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool, comments, seedDate FROM coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
}
$sqldata = mysql_query($sql);
if (!$sqldata) {
   echo "<script>alert(".mysql_error().");</script>";
}
$field = $_POST['fieldID'];
if ($_POST['fieldID']=='%'){$field='All';}
//if($crop1 == "%"  && $crop2 == "%" && $fieldID == "%") {
//echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops in All Fields </caption>";
//} else if($crop1 == "%"  && $crop2 == "%" && $fieldID != "%") {
echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops in Field: ".$field." </caption>";
/*} else if($crop1 == "%" && $crop2 != "%" && $fieldID == "%") { 
echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops that include ".$_POST['crop2']." in All Fields </caption>";
}else if($crop1 == "%"  && $crop2 != "%" && $fieldID != "%") {
echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops that include ".
   $_POST['crop2']." in Field: ".$_POST['fieldID']."  </caption>";
} else if($crop1 != "%" && $crop2 == "%" && $fieldID == "%") { 
echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops that include ".$_POST['crop1']." in All Fields </caption>";
}else if($crop1 != "%"  && $crop2 == "%" && $fieldID != "%") {
echo "<caption> Cover Crop Seeding Report for All Possible Combinations of Cover Crops that include ".$_POST['crop1']." in Field: ".$_POST['fieldID']."  </caption>";
} else if($crop1 != "%" && $crop2 != "%" && $fieldID == "%") {
echo "<caption> Cover Crop Seeding Report for ".$_POST['crop1']." and ".$_POST['crop2']." in All Fields </caption>";
} else if($crop1 != "%" && $crop2 != "%" && $fieldID != "%") {
echo "<caption> Cover Crop Seeding Report for".$_POST['crop1']." and ".$_POST['crop2']." in Field: ".$_POST['fieldID']." </caption>";
}*/

echo "<table>";
//echo "<tr><th>Date</th><th>Crop1</th><th>Pounds1</th><th>Rate1</th><th>Crop2</th><th>Pounds2</th><th>Rate2</th><th>Field</th><th>Area</th><th>Incorporation Tool</th><th> Comments</th></tr>";
/*echo "<tr><th rowspan=2>Date</th><th colspan=3>First Crop</th>".
   "<th colspan=3>Second Crop</th></th><th rowspan=2>Field</th><th rowspan=2>Area</th><th rowspan=2>Incorporation Tool</th><th rowspan=2>Comments</th></tr>";
echo "<tr><th>Crop</th><th>Pounds</th><th>Rate (lbs/acre)</th><th>Crop</th><th>Pounds</th><th>Rate (lbs/acre)</th></tr>";*/
echo "<tr><th style='width:45%;'>Date</th> <th>FieldID</th> <th>Seeding Method</th> <th>Area Seeded</th><th>Incorporation Tool</th><th style='width: 60%;' >Crop</th><th>Comments</th></tr>";
while($row = mysql_fetch_array($sqldata)) {
	$area=number_format($row['areaSeeded'],3,'.','');
	echo "<tr><td>";
	// echo str_replace("-","/",$row['seedDate']);
	echo $row['seedDate'];
	echo "</td><td>";
	echo $row['fieldID'];       
	echo "</td><td>";
	echo $row['seed_method'];
	echo "</td><td>";
	echo $area;
        echo "</td><td>";
	echo $row['incorp_tool'];
	echo "</td><td>";
	// query for coverSeed Table
	$sql = "select * from coverSeed where id=".$row[id]. " order by crop";
	$sqlCoverSeed = mysql_query($sql) or die(mysql_error());
	echo "<table style='width:100%'><tr><th>Crop</th><th>Seeding Rate (lbs/acre)</th><th style='width: 30%;'>Pounds Seeded</th></tr>";
	while ($rowS = mysql_fetch_array($sqlCoverSeed)){
		echo "<tr><td>".$rowS[crop]."</td><td>".$rowS[seedRate]."</td><td>".$rowS[num_pounds]."</td></tr>";
	}
	echo "</table>";
	echo "</td><td>";
	echo $row['comments'];
	echo "</td></tr>";
	echo "\n";
}
	echo "</table>";
$totalAreaSeeded = mysql_query("select sum(((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded) as totalSeeded from coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."'") or die(mysql_error());

$rowTotal = mysql_fetch_array($totalAreaSeeded);
echo "<label for='total'>Total Area Seeded (Acres):&nbsp</label>";
echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".number_format($rowTotal[totalSeeded],3,'.','').">";
echo "<br clear='all'>";
$totalByCrop = mysql_query("select crop, sum(num_pounds) as total from coverSeed natural join coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' group by crop") or die(mysql_error());
while($rowCrop = mysql_fetch_array($totalByCrop)){
	echo "<label for='crop'>Total amount of ".$rowCrop[crop]." seeded:&nbsp</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".number_format($rowCrop[total],3,'.','').">";
	echo "<br clear='all'>";
}
/*
$total="Select sum(num_pounds1) as total, sum(num_pounds2) as total2, 
sum(((Select size from field_GH where fieldID=coverSeed.fieldID)/100)*area_seeded)as area from coverSeed where seedDate between '".
   $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
   "' and (crop1 like '" .$crop1."' or crop1 like '".$crop2.
   "') and (crop2 like '".$crop2."' or crop2 like '".$crop1.
   "') and fieldID like '".$fieldID."'";
$result=mysql_query($total) or die(mysql_error());
echo "<br clear=\"all\">";
while ($row1 = mysql_fetch_array($result)  ) {
	$row3Deci3=number_format((float)$row1['area'], 3, '.', '');
        echo "<label for='total'>Total Area Seeded (Acres):&nbsp</label>";
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
    echo "<br clear=\"all\"/>";
	$row3Deci3=number_format((float)$row1['total'], 3, '.', '');
        if(!$_SESSION['mobile']){
	echo "<label for='total'>Total Number of Pounds Seeded for Cover Crop Species 1:&nbsp</label>";
	} else{
	echo "<label for='total'>Total Lbs Seeded for Cover Crop 1:&nbsp</label>";
	}	
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
    echo "<br clear=\"all\"/>";
	$row3Deci3=number_format((float)$row1['total2'], 3, '.', '');
        if(!$_SESSION['mobile']){
	echo "<label for='total'>Total Number of Pounds Seeded for Cover Crop Species 2:&nbsp</label>";
	} else{
	echo "<label for='total'>Total Lbs for Seeded Cover Crop 2:&nbsp;</label>";
	}
	echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        echo "<br clear=\"all\"/>";
}*/
echo "<br clear=\"all\">";

$sql = "SELECT seedDate, seed_method,fieldID, ((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool,crop, seedRate, num_pounds, comments FROM coverSeed_master natural join coverSeed where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";

        echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "coverReport.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
</div>
