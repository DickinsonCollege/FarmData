<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<!--<form name='form' method='POST' action='/down.php'> -->
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
	<th>CropGroup</th> 
	<th>User</th>
	<th>Comments</th>
	<th>Edit</th>	
</tr>


<?php
$fromDate=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
$toDate=$_GET['tyear']."-".$_GET['tmonth']."-".$_GET['tday'];
$fieldCh=escapehtml($_GET['field']);
$materialCh=escapehtml($_GET['material']);
$sql="SELECT sprayDate, fieldID, (SELECT numOfBed/numberOfBeds FROM field_GH WHERE field_GH.fieldID=tSprayField.fieldID) as percentSprayed, material, tRateUnits,rate, actualTotalAmount*(SELECT size FROM field_GH WHERE field_GH.fieldID= tSprayField.fieldID)/(SELECT SUM(size) FROM field_GH, tSprayField as tf WHERE field_GH.fieldID= tf.fieldID AND tf.id=tSprayMaster.id ) as frac, cropGroup, user, comment
FROM tSprayMaster, tSprayWater, tSprayField, tSprayMaterials
WHERE tSprayMaster.id= tSprayWater.id AND tSprayMaster.id=tSprayField.id AND material LIKE'".$materialCh."' AND tSprayField.fieldID LIKE '".$fieldCh."' AND tSprayMaster.sprayDate BETWEEN '".$fromDate."' AND '".$toDate."'  AND tSprayMaterials.sprayMaterial=tSprayWater.material";
echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
$count=0;
$totalMaterial=0;
$resultM=mysql_query($sql);
while($rowM=mysql_fetch_array($resultM)){
	$count++;
	$totalMaterial=$totalMaterial+$rowM['frac'];
	$theUnit=$rowM['tRateUnits'];	
	echo "<tr><td>".$rowM['sprayDate']."</td>";
	echo "<td>".$rowM['fieldID']."</td>";
	echo "<td>". number_format($rowM['percentSprayed']*100, 2, '.','')."%"."</td>";
	echo "<td>".$rowM['material']."</td>";
	echo "<td>".$rowM['tRateUnits']."</td>";
	echo "<td>".$rowM['rate']."</td>";
	echo "<td>".number_format($rowM['frac'],2,'.','')."</td>";
	echo "<td>".$rowM['cropGroup']."</td>";
	echo "<td>".$rowM['user']." </td>";
	echo "<td>".$rowM['comment']."</td>";
	echo "<td><form method='POST' action='tSprayEdit.php?thisfield=".$rowM[fieldID]."&thismat=".$rowM[material]."&spraydate=".$rowM[sprayDate]."percent=". number_format($rowM['percentSprayed']*100, 2, '.','')."&unit=".$rowM[tRateUnits]."&rate=".$rowM[rate]."&month=".$_GET[month]."&day=".$_GET[day]."&year=".$_GET[year]."&tmonth=".$_GET[tmonth]."&tyear=".$_GET[tyear]."&tday=".$_GET[tday]."&fieldID=".$fieldCh."&material=".$materialCh."&id=".$rowM['id']."&tab=admin:admin_delete:deletesoil:deletespray:tractorspray:edittspray&submit=Submit'><input type='submit' class='submitbutton' value='Edit'></form></td></tr>";

}
echo '</table>';
	if($materialCh!='%'){
	echo "<label for='total'>Total ".$materialCh."  Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".number_format($totalMaterial,2,'.','')."> <label for='total'> &nbsp".$theUnit."</label>";
        echo '<br clear="all"/>';
	echo "<label for='total'>Average Amount of ".$materialCh." Used:&nbsp</label>";
        echo "<input class='textbox2' style='width: 120px;' type ='text' value=".number_format($totalMaterial/$count,2,'.','')."> <label for='total'> &nbsp".$theUnit."</label>";

	}	
        echo '<br clear = "all"/>';
        echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
//echo "</form>";

echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

?>
