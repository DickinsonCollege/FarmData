<?php session_start(); ?>
<?php
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' method='POST' action='/down.php'>
<table >
<caption> Tractor Spraying Report </caption>
<tr>
	<th >Date</th>
	<th>FieldID</th>
	<th>%Sprayed</th>
	 <th>Material</th>
	 <th>Units</th>
	 <th>Rate</th>
	 <th>Total</th>
	 <th>Crops</th> 
	 <th>User</th>
	 <th>Comments</th>
</tr>


<?php
$fromDate=$_POST['year']."-".$_POST['month']."-".$_POST['day'];
$toDate=$_POST['tyear']."-".$_POST['tmonth']."-".$_POST['tday'];
$fieldCh=escapehtml($_POST['fieldID']);
$materialCh=escapehtml($_POST['material']);
$crop=escapehtml($_POST['crop']);
$sql="SELECT sprayDate, fieldID, (SELECT numOfBed/numberOfBeds FROM field_GH WHERE field_GH.fieldID=tSprayField.fieldID) as percentSprayed, material, tRateUnits,rate, actualTotalAmount*(SELECT size FROM field_GH WHERE field_GH.fieldID= tSprayField.fieldID)/(SELECT SUM(size) FROM field_GH, tSprayField as tf WHERE field_GH.fieldID= tf.fieldID AND tf.id=tSprayMaster.id ) as frac, crops, user, comment
FROM tSprayMaster, tSprayWater, tSprayField, tSprayMaterials
WHERE tSprayMaster.id= tSprayWater.id AND tSprayMaster.id=tSprayField.id AND material LIKE'".
$materialCh."' AND tSprayField.fieldID LIKE '".$fieldCh."' AND crops like '%".$crop.
   "%' and tSprayMaster.sprayDate BETWEEN '".$fromDate."' AND '".$toDate."'  AND tSprayMaterials.sprayMaterial=tSprayWater.material";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
$count=0;
$totalMaterial=0;
$resultM=mysql_query($sql);
echo mysql_error();
	while($rowM=mysql_fetch_array($resultM)){
	$count++;
	$totalMaterial=$totalMaterial+$rowM['frac'];
	$theUnit=$rowM['tRateUnits'];	
	//echo "<tr><td>".str_replace("-","/",$rowM['sprayDate'])."</td><td>".$rowM['fieldID']."</td><td>". number_format($rowM['percentSprayed']*100, 2, '.','')."%"."</td><td>".$rowM['material']."</td><td>".$rowM['tRateUnits']."</td><td>".$rowM['rate']."</td><td>".number_format($rowM['frac'],2,'.','')."</td><td>".$rowM['cropGroup']."</td><td>".$rowM['user']." </td><td>".$rowM['comment']."</td></tr>";
	echo "<tr><td>".$rowM['sprayDate']."</td><td>".$rowM['fieldID']."</td><td>".
             number_format($rowM['percentSprayed']*100, 2, '.','')."%"."</td><td>".
             $rowM['material']."</td><td>".$rowM['tRateUnits']."</td><td>".$rowM['rate'].
             "</td><td>".number_format($rowM['frac'],2,'.','')."</td><td>".$rowM['crops'].
             "</td><td>".$rowM['user']." </td><td>".$rowM['comment']."</td></tr>";
	}
echo '</table>';
	if($materialCh!='%'){
	echo "<label for='total'>Total ".$materialCh."  Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".number_format($totalMaterial,2,'.','')."> <label for='total'> &nbsp".$theUnit."</label>";
        echo '<br clear="all"/>';
	echo "<label for='total'>Average Amount of ".$materialCh." Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".number_format($totalMaterial/$count,2,'.','')."> <label for='total'> &nbsp".$theUnit."</label>";

	}	

/*	
space(1);
$total = "Select tSprayMaster.id, TRateUnits as unit, sum(actualTotalAmount) as total2, avg(actualTotalAmount) as average2, avg(numOfBed/numberOfBeds)*100 as average FROM field_GH, tSprayMaster, tSprayField, tSprayWater, tSprayMaterials WHERE sprayDate between '".$fromDate."' AND '".$toDate."' AND tSprayField.fieldID LIKE '".$fieldCh. "'  AND material LIKE '".$materialCh."' AND tSprayField.id=tSprayMaster.id AND tSprayWater.id=tSprayMaster.id AND field_GH.fieldID=tSprayField.fieldID  AND tSprayWater.material=sprayMaterial order by tSprayMaster.sprayDate, tSprayField.fieldID";
$result = mysql_query($total);
while($row1 = mysql_fetch_array($result)) {
	$row3Deci3=number_format((float)$row1['total2'], 2, '.', '');
        echo "<label for='total'>Total Material Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        echo "<label for='unit'>&nbsp;".$row1['unit']."(S)</label>";
	space(2);
	$row3Deci3=number_format((float)$row1['average2'], 2, '.', '');
        echo "<label for='total'>Average Amount of Material Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        echo "<label for='unit'>&nbsp;".$row1['unit']."(S)</label>";
        space(2);
	$row3Deci3=number_format((float)$row1['average'], 2, '.', '');
        echo "<label for='total'>Average Percent of Field Sprayed:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".$row3Deci3."%>";
        space(1);

	}

if(!$_SESSION['mobile']) {
space(1);
}else {
space(1);
}
echo '<label for="download"> Please click the button below to download the report  just generated </label>';
        space(3);
*/
        echo '<br clear = "all"/>';
        echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo "</form>";

echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

?>
