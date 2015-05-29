<?php session_start();
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
// echo '<html>';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
 ?>
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

$fromDate=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
$toDate=$_GET['tyear']."-".$_GET['tmonth']."-".$_GET['tday'];
$fieldCh=escapehtml($_GET['fieldID']);
$materialCh=escapehtml($_GET['material']);
$crop=escapehtml($_GET['crop']);
if (!$_GET['inst']) {
echo "<form name='form' class='pure-form pure-form-aligned' method='POST' action='/down.php'>";
echo "<center>";
echo "<h2> Tractor Spraying Report </h2>";
echo "</center>";
echo "<table class='pure-table pure-table-bordered'>";
echo "<thead><tr> <th >Date</th> <th>FieldID</th> <th>%Sprayed</th> <th>Material</th> <th>Units</th>";
echo "<th>Rate</th> <th>Total</th> <th>Crops</th> <th>Comments</th><th>Initials</th> </tr></thead>";

$sql="SELECT sprayDate, fieldID, (SELECT numOfBed/numberOfBeds FROM field_GH ".
   "WHERE field_GH.fieldID=tSprayField.fieldID) as percentSprayed, material, tRateUnits,rate, ".
   "initials, actualTotalAmount*(SELECT size FROM field_GH ".
       "WHERE field_GH.fieldID= tSprayField.fieldID)/".
       "(SELECT SUM(size) FROM field_GH, tSprayField as tf ".
       "WHERE field_GH.fieldID= tf.fieldID AND tf.id=tSprayMaster.id ) as frac, tSprayField.crops, user, comment ".
       "FROM tSprayMaster, tSprayWater, tSprayField, tSprayMaterials ".
       "WHERE tSprayMaster.id= tSprayWater.id AND tSprayMaster.id=tSprayField.id AND material LIKE'".
       $materialCh."' AND tSprayField.fieldID LIKE '".$fieldCh."' AND tSprayField.crops like '%".$crop.
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
        echo '<br clear = "all"/>';
        echo "<div class='pure-control-group'>";
	echo "<label for='total'>Total ".$materialCh."  Used:</label> ";
        echo "<input class='textbox2' readonly type ='text' value=".number_format($totalMaterial,2,'.','')."> &nbsp".$theUnit."(S)";
        echo '</div>';
        echo "<div class='pure-control-group'>";
	echo "<label for='total'>Average Amount of ".$materialCh." Used:</label> ";
        echo "<input class='textbox2' readonly type ='text' value=".number_format($totalMaterial/$count,2,'.','')."> &nbsp".$theUnit."(S)";
        echo '</div>';

	}	

        echo '<br clear = "all"/>';
        echo "<div class='pure-g'>";
        echo "<div class='pure-u-1-2'>";
        echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo "</form>";
        echo "</div>";
        echo "<div class='pure-u-1-2'>";
} else {
$numRows = 0;
echo "<center>";
echo "<h2> Tractor Spraying Instructions </h2>";
echo "</center>";
echo "<div class='pure-form pure-form-aligned'>";
echo "<table class='pure-table pure-table-bordered'>";
echo "<thead><tr> <th >Date</th> <th>Fields</th><th>Materials</th><th>Water</th>";
echo "<th>Additional Instructions</th><th>Initials</th><th>Complete</th></tr></thead>";
$masterSQL = "select tSprayMaster.id, sprayDate, waterPerAcre, comment, initials from tSprayMaster where sprayDate BETWEEN '".
  $fromDate."' AND '".$toDate."' and complete=0 and exists (select * from tSprayField where tSprayField.id = tSprayMaster.id and tSprayField.crops like '%".$crop."%')";
$result = mysql_query($masterSQL);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   $numRows++;
   $id = $row['id'];
   echo "<tr><td>".$row['sprayDate']."</td><td>";
   echo "<center><table class='pure-table pure-table-bordered' ><thead><tr><th>Field Name</th><th>Beds to Spray</th><th>Crops</th></tr></thead><tbody>";
   $sql = "select tSprayField.fieldID, numOfBed, numberOfBeds, crops from tSprayField, field_GH".
     " where tSprayField.fieldID LIKE '".
     $fieldCh."' and tSprayField.fieldID = field_GH.fieldID and id = ".$id;
   $fRes = mysql_query($sql);
   echo mysql_error();
   while ($fRow = mysql_fetch_array($fRes)) {
       echo "<tr><td>".$fRow['fieldID']."</td><td>".$fRow['numOfBed']."&nbsp;(".
         number_format((float) $fRow['numOfBed'] * 100 / $fRow['numberOfBeds'], 0, '.', '').
         "%)</td><td>".$fRow['crops']."</td></tr>";
   }
   echo "</tbody></table></center></td><td>";
   echo "<center><table class='pure-table pure-table-bordered'><thead><tr><th>Material</th><th>Amount</th><th>Unit</th></tr></thead>";
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

   echo "</td>";
   echo "<td>".$row['comment']."</td><td>";
   echo "<input class='wide' type='text' size = '5' id='initials".$numRows.
      "' name='initials".$numRows."' value='".$row['initials']."' style='width:100%'></td><td>";
   echo '<input class = "submitbutton pure-button wide" type="button" name="done'.$numRows.
       '" value="Done" onclick="complete('.$numRows.', '.$id.');"/>';
   echo "</td></tr>";
}
echo "</table></div>";
echo "<input type='hidden' name='numRows' value=".$numRows.">";
echo '<br clear="all"/>';
/*
echo "</form>";
*/
}

echo '<form method="POST" action = "reportChooseDate.php?tab=soil:soil_spray:tspray:tspray_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
if (!$_GET['inst']) {
   echo "</div></div>";
}

?>
