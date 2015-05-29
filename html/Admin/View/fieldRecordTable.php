<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$fieldID = escapehtml($_GET["fieldID"]);
$farm = $_SESSION['db'];

echo '<center><h2>Field Record for Field:&nbsp;'.$fieldID.'</h2></center>';
$sql = "select  size, numberOfBeds, length from field_GH where fieldID = '".$fieldID."'";;
$sqldata = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$length = $row['length'];
$size = $row['size'];
$numBeds = $row['numberOfBeds'];

echo '<h3>Field Characteristics: Length '.$length.' ft, '.$numBeds.' Beds, '.$size.' acres</h3>';

$sql="select plantdate,crop,bedft,rowsBed,bedft * rowsBed as rowft, ".
   "comments from dir_planted where fieldID like '".$fieldID."' and plantdate between '".$year."-".
   $month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
   "' order by plantdate ";
$sqldata = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($sqldata)==0) {
   echo '<h3>DIRECT SEEDING</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<h3>DIRECT SEEDING</h3>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Bed Feet</th><th>Rows/Bed</th><th>Row Feet</th><th> Comments </th></tr></thead>";
   while ( $row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['plantdate'];
      echo "</td><td>";
      echo $row['crop'];
      echo "</td><td>";
      echo number_format((float) $row['bedft'], 1, '.', '');
      echo "</td><td>";
      echo $row['rowsBed'];
      echo "</td><td>";
      echo number_format((float) $row['rowft'], 1, '.', '');
      echo "</td><td>";
      echo $row['comments'];
      echo "</td></tr>";
   }
   echo "</table>";
}
echo '<br clear="all"/>';

$sql="Select crop,bedft, rowsBed, bedft * rowsBed as rowft, transdate, comments from  transferred_to where ".
   "transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
   "' and fieldID like '".$fieldID."' order by transdate";
$sqldata = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($sqldata)==0) {
   echo '<h3>TRANSPLANTING</h3><br clear="all"/>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<h3>TRANSPLANTING</h3>";
   echo "<thead><tr><th>Date</th><th>Crop<center></th><th>Bed Feet</th><th>Rows/Bed</th><th><center>Row Feet</center></th><th><center> Comments</center></th></tr></thead>";
   while ($row= mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['transdate'];
      echo "</td><td>";
      echo $row['crop'];
      echo "</td><td>";
      echo number_format((float) $row['bedft'], 1, '.', '');
      echo "</td><td>";
      echo $row['rowsBed'];
      echo "</td><td>";
      echo number_format((float) $row['rowft'], 1, '.', '');
      echo "</td><td>";
      echo $row['comments'];
      echo "</td></tr>";
   }
   echo "</table>";
}
echo '<br clear="all"/>';

$cropsql = "select distinct crop from harvested where hardate BETWEEN '".$year."-".$month."-".$day."' AND '".
   $tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID."' order by crop";
$cropdata = mysql_query($cropsql) or die(mysql_error());
if (mysql_num_rows($cropdata)==0) {
   echo '<h3>HARVESTING</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<h3>HARVESTING</h3>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Unit</th><th>Yield</th><th>  Comments  </th></tr></thead>";
   while ($rowcrop = mysql_fetch_array($cropdata)) {
      $crop = $rowcrop['crop'];
      $totyield = 0;
      $sql = "SELECT hardate, crop,yield,unit,comments FROM harvested where hardate BETWEEN '".$year.
         "-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and harvested.crop like '".
         $crop."' and fieldID like '".$fieldID."' order by hardate";
      $sqldata = mysql_query($sql) or die(mysql_error());
      $unit='';
      while ($row = mysql_fetch_array($sqldata)) {
         $totyield += $row['yield'];
         echo "<tr><td>";
         echo $row['hardate'];
         echo "</td><td>";
         echo $row['crop'];
         echo "</td><td>";
         echo $unit = $row['unit'];
         echo "</td><td>";
         echo $row['yield'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
         echo "\n";
      }
      if ($farm != 'wahlst_spiralpath') {
         $sqlfeet = "select sum(rowft) as sumrowft from ".
           "(select bedft * rowsBed as rowft from dir_planted where crop like '".$crop.
           "' and fieldID like '".$fieldID."' and plantdate between '".$year."-01-01' AND '".
           $tcurYear."-".$tcurMonth."-".$tcurDay."' union ".
           "select bedft * rowsBed as rowft from  transferred_to where ".
           "transdate between '".$year."-01-01' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
           "' and fieldID like '".$fieldID."' and crop like '".$crop."') as planted";
         $feetdata = mysql_query($sqlfeet) or die(mysql_error());
         $feetrow = mysql_fetch_array($feetdata);
         $feet = $feetrow['sumrowft'];
         echo '<tr style="background:#ADD8E6"><td>Total Yield:</td><td>'.$crop.'</td><td>'.$totyield.' '.
            $unit.'</td><td>'.number_format((float) ($totyield/$feet), 2, '.', '').' '.$unit.
            '/Row Foot</td><td>&nbsp;</td></tr>';
      }
   }
   echo "</table>";
}
echo '<br clear="all"/>';

if ($_SESSION['tillage']) {
   $sql = "Select tractorName, tilldate, tool, num_passes, comment,  percent_filled from tillage where tilldate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."'  order by tilldate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>TILLAGE</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered>";
      echo "<h3>TILLAGE</h3>";
   
      echo "<thead><tr><th>Date</th><th>Tractor</th><th>Implement</th><th>% tilled</th><th>Passes</th><th>Comments</th></tr></thead>";
      while ($row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['tilldate'];
         echo "</td><td>";
         echo $row['tractorName'];
         echo "</td><td>";
         echo $row['tool'];       
         echo "</td><td>";
         echo $row['percent_filled'];
         echo "</td><td>";
         echo $row['num_passes'];
         echo "</td><td>";
         echo $row['comment'];
         echo "</td><tr>";
         echo "\n";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';

if ($_SESSION['compost']) {
   $sql="select util_date,incorpTool,pileID,tperacre,incorpTiming,fieldSpread,comments from utilized_on where util_date between '"
      .$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."' order by util_date";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>COMPOST</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>COMPOST</h3>";
      echo "<thead><tr><th> Date</th><th>Pile ID</th><th>Incorporation Tool</th><th>Incorporation Timing</th><th>Tons per Acre</th><th>Comments</th></tr></thead>";
      while ( $row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['util_date'];
         echo "</td><td>";
         echo $row['pileID'];
         echo "</td><td>";
         echo $row['incorpTool'];
         echo "</td><td>";
         echo $row['incorpTiming'];
         echo "</td><td>";
         echo number_format((float) $row['tperacre'], 2, '.', '');
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['irrigation']) {
   $sql = "select * from pump_field, pump_master  where pump_field.id = pump_master.id and pumpDate between '".
      $year."-".$month."-".$day."' AND '".  $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".
     $fieldID."' order by pumpDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>IRRIGATION</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>IRRIGATION</h3>";
      echo "<thead><tr><th>Date</th><th>Irrigation Device</th><th>Hours</th><th>Comments</th></tr></thead>";
      while ($row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['pumpDate'];
         echo "</td><td>";
         echo $row['irr_device'];
         echo "</td><td>";
         echo number_format(((float) $row['elapsed_time'])/60, 2, '.', '');
         echo "</td><td>";
         echo $row['comment'];
         echo "</td></tr>";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['liquidfertilizer']) {
   $sql = "select * from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' order by inputDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>LIQUID FERTILIZER</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>LIQUID FERTILIZER</h3>";
      echo "<thead><tr><th>Date</th><th>Material</th><th>Total Material Applied</th><th>Unit</th><th>Drip Rows</th><th>Comments</th></tr></thead>";
      while ($row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['inputDate'];
         echo "</td><td>";
         echo $row['fertilizer'];
         echo "</td><td>";
         echo number_format((float) $row['quantity'], 2, '.', '');
         echo "</td><td>";
         echo $row['unit'];
         echo "</td><td>";
         echo $row['dripRows'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td><tr>";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['dryfertilizer']) {
   $sql = "select inputDate, fertilizer, crops, rate, numBeds, totalApply, comments ".
      "from fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' order by inputDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>DRY FERTILIZER</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>DRY FERTILIZER</h3>";
      echo "<thead><tr><th>Date</th><th>Product</th><th>Application Rate<br>"."(lbs/acre)</th><th>Bed Feet</th><th>Total Applied</th><th>Comments</th></tr></thead>";
      while ($row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['inputDate'];
         echo "</td><td>";
         echo $row['fertilizer'];
         echo "</td><td>";
         echo $row['rate'];
         echo "</td><td>";
         echo $row['numBeds'] * $length;
         echo "</td><td>";
         echo $row['totalApply'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td><tr>";
         echo "\n";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}
	/*
   $sql = "SELECT coverSeed.crop1, coverSeed.crop2, seedRate1,seedRate2, ((Select size from field_GH ".
      "where fieldID=coverSeed.fieldID)/100)*area_seeded as areaSeeded,num_pounds1, num_pounds2, ".
      "seed_method, incorp_tool, comments, seedDate FROM coverSeed where seedDate BETWEEN '".$year."-".
      $month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID.
      "' order by seedDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo 'COVER CROP SEEDING<br clear="all"/>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table>";
      echo "<caption>COVER CROP SEEDING</caption>";
      echo "<tr><th>&nbsp;</th><th colspan='3'>First Cover Crop</th><th colspan='3'>Second Cover Crop</th>".
         "<th>&nbsp;</th><th>&nbsp;</th> <th>&nbsp;</th></tr>";
      echo "<tr><th>Date</th><th>Crop</th><th>Pounds Seeded</th><th>Seeding Rate (lbs/acre)</th>".
         "<th>Crop</th><th>Pounds Seeded</th><th>Seeding Rate (lbs/acre)</th><th>Area (acres)</th>".
         "<th>Incorporation Tool</th><th>Comments</th></tr>";
      while($row = mysql_fetch_array($sqldata)) {
         $area=number_format($row['areaSeeded'],3,'.','');
         echo "<tr><td>";
         echo $row['seedDate'];
         echo "</td><td>";
         echo $row['crop1'];
         echo "</td><td>";
         echo $row['num_pounds1'];
         echo "</td><td>";
         echo $row['seedRate1'];
         echo "</td><td>";
         echo $row['crop2'];
         echo "</td><td>";
         echo $row['num_pounds2'];
         echo "</td><td>";
         echo $row['seedRate2'];
         echo "</td><td>";
         echo $area;
         echo "</td><td>";
         echo $row['incorp_tool'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
         echo "\n";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';*/
if ($_SESSION['cover']) {
	/******   COVER CROP TABLE       */
	$sql = "SELECT id, seed_method,fieldID, ((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool, comments, seedDate FROM coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
	
	$sqldata = mysql_query($sql);
	if (!$sqldata) {
   	echo "<script>alert(".mysql_error().");</script>";
	}
	
	$field = $_POST['fieldID'];
	if ($_POST['fieldID']=='%'){$field='All';}
	if (mysql_num_rows($sqldata)==0) {
      echo '<h3>COVER CROP SEEDING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
		echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>COVER CROP SEEDING</h3>";
		echo "<thead><tr><th style='width:45%;'>Date</th> <th>FieldID</th> <th>Seeding Method</th> <th>Area Seeded</th><th>Incorporation Tool</th><th style='width: 60%;' >Crop</th><th>Comments</th></tr></thead>";
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
   		$sql = "select * from coverSeed where id=".$row[id].
                    " order by crop";
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
	}
	echo '<br clear="all"\>';


   $sql="select killDate, seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
		totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
		FROM coverKill_master 
		WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND fieldID LIKE '".$fieldID."' 
		ORDER BY killDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>COVER CROP INCORPORATION</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>COVER CROP INCORPORATION</h3>";
      echo "<thead><tr><th>Date</th><th>Cover Crop</th><th>Seed Date</th><th>Incorporation Tool</th>".
         "<th>Total Biomass</th><th> Biomass Pounds Per Acre </th><th>Comments</th></tr></thead>";
      while($row = mysql_fetch_array($sqldata)) {
         $var=number_format($row['bioPerAcre'],2,'.','');
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
         echo $row['incorpTool'];
         echo "</td><td>";
         $row3Deci3=number_format((float)$row['totalBiomass'], 3, '.', '');
         echo $row3Deci3;
         echo "</td><td>";
         echo $var;
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
      }
		$sqlget = "Select sum(totalBiomass) as total, avg(totalBiomass) as average from coverKill_master where killDate between 
			'".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID."'";
		$result = mysql_query($sqlget);
		while ($row1 = mysql_fetch_array($result)) {
			$totalBio = number_format($row1['total'], 3, '.', '');
			$averageBio = number_format((float)$row1['average'], 3, '.', '');
			echo '<tr style="background:#ADD8E6"><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>Total Biomass:</td><td>'.$totalBio.'</td><td>Average Biomass:</td><td>'.$averageBio.'</td></tr>';
		}
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['insect']) {
   $sql="select sDate,crops,pest,avgCount,comments from pestScout where sDate between '".$year."-".$month.
      "-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID.
      "' order by sDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>INSECT SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered>";
      echo "<h3>INSECT SCOUTING</h3>";
      echo "<thead><tr><th>Date</th><th>Crops</th><th>Insect</th><th>Average Count</th><th>Comments</th></tr></thead>";
      while ( $row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['sDate'];
         echo "</td><td>";
         echo $row['crops'];
         echo "</td><td>";
         echo $row['pest'];
         echo "</td><td>";
         echo $row['avgCount'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['weed']) {
   $sql="Select sDate,weed,infestLevel,goneToSeed,comments from weedScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."' order by sDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>WEED SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>WEED SCOUTING</h3>";
      echo "<thead><tr><th>Date</th><th>Species</th><th>Infestation Level</th><th>% Gone to Seed</th>".
         "<th>Comment</th></tr></thead>";
      while ( $row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['sDate'];
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
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['disease']) {
   $sql="Select sDate,crops,disease,infest,stage,comments from diseaseScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."'";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>DISEASE SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>DISEASE SCOUTING</h3>";
      echo "<thead><tr><th>Date</th><th>Crops</th><th>Disease Species</th><th>Infestation Level</th>".
         "<th>Crop Stage</th><th>Comments</th></tr></thead>";
      while ( $row = mysql_fetch_array($sqldata)) {
         echo "<tr><td>";
         echo $row['sDate'];
         echo "</td><td>";
         echo $row['crops'];
         echo "</td><td>";
         echo $row['disease'];
         echo "</td><td>";
         echo $row['infest'];
         echo "</td><td>";
         echo $row['stage'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['backspray']) {
   $sql = "Select sprayDate, materialSprayed, rate, BRateUnits, totalMaterial, crops, comments from ".
     "bspray, tSprayMaterials where sprayMaterial = materialSprayed ".
     "and sprayDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
     "' and fieldID like '".$fieldID."' order by sprayDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>BACKPACK SPRAYING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>BACKPACK SPRAYING</h3>";
      echo "<thead><tr><th>Date</th><th>Material Sprayed</th><th>Rate</th><th>Total Material</th>".
         "<th>Crops</th><th> Comments </th></tr></thead>";
      while($row = mysql_fetch_array($sqldata)) {
         $unit = $row['BRateUnits'];
         echo "<tr><td>";
         echo $row['sprayDate'];
         echo "</td><td>";
         echo $row['materialSprayed'];
         echo "</td><td>";
         echo $row['rate'].' '.$unit.'/Gallon';
         echo "</td><td>";
         echo $row['totalMaterial'].' '.$unit;
         echo "</td><td>";
         echo $row['crops'];
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
         echo "\n";
      }
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['tractorspray']) {
   $sql="SELECT sprayDate, (SELECT numOfBed/numberOfBeds FROM field_GH WHERE field_GH.fieldID = ".
     "tSprayField.fieldID) as percentSprayed, material, tRateUnits, rate, actualTotalAmount * ".
     "(SELECT size FROM field_GH WHERE field_GH.fieldID= tSprayField.fieldID)/(SELECT SUM(size) FROM ".
     "field_GH, tSprayField as tf WHERE field_GH.fieldID = tf.fieldID AND tf.id=tSprayMaster.id) as frac,".
      " crops, user, comment FROM tSprayMaster, tSprayWater, tSprayField, tSprayMaterials".
      " WHERE tSprayMaster.id= tSprayWater.id AND tSprayMaster.id=tSprayField.id AND tSprayField.fieldID ".
      "LIKE '".$fieldID."' AND tSprayMaster.sprayDate BETWEEN '".$year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-".$tcurDay."' AND tSprayMaterials.sprayMaterial=tSprayWater.material".
      " order by sprayDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   if (mysql_num_rows($sqldata)==0) {
      echo '<h3>TRACTOR SPRAYING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<h3>TRACTOR SPRAYING</h3>";
      echo '<thead><tr><th>Date</th><th>% Sprayed</th> <th>Material</th> <th>Rate</th> <th>Total Material</th>'.
         '<th>Crops</th> <th>Comments</th> </tr></thead>';
      while($rowM=mysql_fetch_array($sqldata)){
         $theUnit=$rowM['tRateUnits'];
         echo "<tr><td>".$rowM['sprayDate']."</td><td>".
            number_format($rowM['percentSprayed']*100, 2, '.','')."%"."</td><td>".$rowM['material'].
           "</td><td>".$rowM['rate']." ".$theUnit."/Acre</td><td>".
           number_format($rowM['frac'],2,'.','')." ".$theUnit."</td><td>".$rowM['crops']."</td><td>".
           $rowM['comment']."</td></tr>";
      }
      echo '</table>';
   }
   echo '<br clear="all"/>';
}
}

if ($_SESSION['labor']) {
$sql = "SELECT hours, ldate,crop,task,comments FROM laborview where ldate BETWEEN '".$year."-".$month.
   "-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID.
   "' and hours > 0 order by crop, ldate";
$sqldata = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($sqldata)==0) {
   echo '<h3>LABOR</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<h3>LABOR</h3>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Task</th><th>Hours</th><th>Comments </th></tr></thead>";
   $hours=0;
   while($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['ldate'];
      echo "</td><td>";
      echo $row['crop'];
      echo "</td><td>";
      echo $row['task'];
      echo "</td><td>";
      #echo $row['hours'];
      echo number_format((float) $row['hours'], 2, '.', '');
      $hours += $row['hours'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td></tr>";
      echo "\n";
   }
   echo '<tr style="background:#ADD8E6"><td>&nbsp;</td><td>&nbsp;</td><td>Total Hours:</td><td>'.
      number_format((float) $hours, 2, '.', '').
      '</td><td>&nbsp;</td></tr>';
   echo '</table>';
}
echo '<br clear="all"/>';
}

?>
</div>
</body>
</html>
