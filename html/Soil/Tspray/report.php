<?php session_start(); ?>
<script type="text/javascript">

function complete(row, id) {
   var init = document.getElementById("initials" + row).value;
   if (init == "") {
      alert("Please enter your initials!");
      return false;
   }
   console.log(id);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "complete_tspray.php?id="+id+"&init="+encodeURIComponent(init), false);
   xmlhttp.send();
   if (xmlhttp.responseText != "\n") {
       alert(xmlhttp.responseText);
   }
   window.location = window.location.href;
   return true;
}
</script>
<?php
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

$fromDate=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
$toDate=$_GET['tyear']."-".$_GET['tmonth']."-".$_GET['tday'];
$fieldCh=escapehtml($_GET['fieldID']);
$materialCh=escapehtml($_GET['material']);
$crop=escapehtml($_GET['crop']);
if (!$_GET['inst']) {
echo "<form name='form' method='POST' action='/down.php'>";
echo "<table >";
echo "<caption> Tractor Spraying Report </caption>";
echo "<tr> <th >Date</th> <th>FieldID</th> <th>%Sprayed</th> <th>Material</th> <th>Units</th>";
echo "<th>Rate</th> <th>Total</th> <th>Crops</th> <th>Comments</th><th>Initials</th> </tr>";

$sql="SELECT sprayDate, fieldID, (SELECT numOfBed/numberOfBeds FROM field_GH ".
   "WHERE field_GH.fieldID=tSprayField.fieldID) as percentSprayed, material, tRateUnits,rate, ".
   "initials, actualTotalAmount*(SELECT size FROM field_GH ".
       "WHERE field_GH.fieldID= tSprayField.fieldID)/".
       "(SELECT SUM(size) FROM field_GH, tSprayField as tf ".
       "WHERE field_GH.fieldID= tf.fieldID AND tf.id=tSprayMaster.id ) as frac, crops, user, comment ".
       "FROM tSprayMaster, tSprayWater, tSprayField, tSprayMaterials ".
       "WHERE tSprayMaster.id= tSprayWater.id AND tSprayMaster.id=tSprayField.id AND material LIKE'".
       $materialCh."' AND tSprayField.fieldID LIKE '".$fieldCh."' AND crops like '%".$crop.
       "%' and tSprayMaster.sprayDate BETWEEN '".$fromDate."' AND '".$toDate.
       "' AND tSprayMaterials.sprayMaterial=tSprayWater.material and complete = 1";
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
             "</td><td>".
// $rowM['user']."</td><td>".
             $rowM['comment']."</td><td>".$rowM['initials']."</td></tr>";
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
} else {
/*
   echo '<form method="POST" action = "update_instructions.php">';
$year=$_POST['year'];
$month=$_POST['month'];
$da."-".$_POST['day'];
$toDate=$_POST['tyear']."-".$_POST['tmonth']."-".$_POST['tday'];
$fieldCh=escapehtml($_POST['fieldID']);
$materialCh=escapehtml($_POST['material']);
$crop=escapehtml($_POST['crop']);
*/
echo "<table >";
$numRows = 0;
echo "<caption> Tractor Spraying Instructions </caption>";
echo "<tr> <th >Date</th> <th>Fields</th><th>Materials</th><th>Water</th>";
echo "<th>Additional Instructions</th><th>Initials</th><th>Complete</th></tr>";
$masterSQL = "select id, sprayDate, waterPerAcre, comment, initials from tSprayMaster where sprayDate BETWEEN '".
  $fromDate."' AND '".$toDate."' and complete=0";
$result = mysql_query($masterSQL);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   $numRows++;
   $id = $row['id'];
   echo "<tr><td>".$row['sprayDate']."</td><td>";
   echo "<center><table style='width:5%'><tr><th>FieldID</th><th>Beds to Spray</th></tr>";
   $sql = "select tSprayField.fieldID, numOfBed, numberOfBeds from tSprayField, field_GH".
     " where tSprayField.fieldID LIKE '".
     $fieldCh."' and tSprayField.fieldID = field_GH.fieldID and id = ".$id;
   $fRes = mysql_query($sql);
   echo mysql_error();
   while ($fRow = mysql_fetch_array($fRes)) {
       echo "<tr><td>".$fRow['fieldID']."</td><td>".$fRow['numOfBed']."&nbsp;(".
         number_format((float) $fRow['numOfBed'] * 100 / $fRow['numberOfBeds'], 0, '.', '').
         "%)</td></tr>";
   }
   echo "</table></center></td><td>";
   echo "<center><table style='width:5%'><tr><th>Material</th><th>Amount</th><th>Unit</th></tr>";
   $sql = "select material, actualTotalAmount, TRateUnits from tSprayWater, tSprayMaterials where id = ".
      $id." and tSprayWater.material = tSprayMaterials.sprayMaterial and material LIKE'".
     $materialCh."'";
   $mRes = mysql_query($sql);
   echo mysql_error();
   while ($mRow = mysql_fetch_array($mRes)) {
       echo "<tr><td>".$mRow['material']."</td><td>".$mRow['actualTotalAmount']."</td><td>";
       echo $mRow['TRateUnits']."</td></tr>";
   }
   echo "</table></center></td><td>";
   $watersql = "select sum(numOfBed * size/numberOfBeds) as acres from tSprayField, field_GH where".
     " tSprayField.fieldID = field_GH.fieldID and id=".$id." and tSprayField.fieldID like '".
     $fieldCh."'";
   $wRes = mysql_query($watersql);
   echo mysql_error();
   while ($wRow = mysql_fetch_array($wRes)) {
      echo number_format((float) $wRow['acres'] * $row['waterPerAcre'], 1, '.', '');
      echo " GALLONS";
   }

   echo "<td>".$row['comment']."</td><td>";
   echo "<input class='textbox mobile-input inside_table' type='text' id='initials".$numRows.
      "' name='initials".$numRows."' value='".$row['initials']."' style='width:100%'></td><td>";
   echo '<input class = "submitbutton" type="button" name="done'.$numRows.
       '" value="Done" onclick="complete('.$numRows.', '.$id.');"/>';
   echo "</td></tr>";
}
echo "</table>";
echo "<input type='hidden' name='numRows' value=".$numRows.">";
echo '<br clear="all"/>';
/*
echo "</form>";
*/
}

echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

?>
