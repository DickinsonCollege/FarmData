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
$sql = "select  size, numberOfBeds, length from field_GH where fieldID = '".$fieldID."'";
$sqldata = $dbcon->query($sql);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$length = $row['length'];
$size = $row['size'];
$numBeds = $row['numberOfBeds'];

echo '<h3>Field Characteristics: Length '.$length.' ft, '.$numBeds.' Beds, '.$size.' acres</h3>';

$sql="select plantdate,crop,bedft,rowsBed,bedft * rowsBed as rowft, ".
   "comments from dir_planted where fieldID like '".$fieldID."' and plantdate between '".$year."-".
   $month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
   "' order by plantdate ";
$sqldata = $dbcon->query($sql);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
if (!$row) {
   echo '<h3>DIRECT SEEDING</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<h3>DIRECT SEEDING</h3>";
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Bed Feet</th><th>Rows/Bed</th><th>Row Feet</th>".
      "<th> Comments </th></tr></thead>";
   do {
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
   } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
   echo "</table>";
}
echo '<br clear="all"/>';

$sql="Select crop,bedft, rowsBed, bedft * rowsBed as rowft, transdate, comments from  transferred_to where ".
   "transdate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
   "' and fieldID like '".$fieldID."' order by transdate";
$sqldata = $dbcon->query($sql);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
if (!$row) {
   echo '<h3>TRANSPLANTING</h3><br clear="all"/>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<h3>TRANSPLANTING</h3>";
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Date</th><th>Crop<center></th><th>Bed Feet</th><th>Rows/Bed</th><th><center>Row Feet</center></th><th><center> Comments</center></th></tr></thead>";
   do {
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
   } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
   echo "</table>";
}
echo '<br clear="all"/>';

$cropsql = "select distinct crop from harvested where hardate BETWEEN '".$year."-".$month."-".$day."' AND '".
   $tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID."' order by crop";
$cropdata = $dbcon->query($cropsql);
$rowcrop = $cropdata->fetch(PDO::FETCH_ASSOC);
if (!$rowcrop) {
   echo '<h3>HARVESTING</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<h3>HARVESTING</h3>";
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Unit</th><th>Yield</th><th>  Comments  </th></tr></thead>";
   do {
      $crop = $rowcrop['crop'];
      $totyield = 0;
      $sql = "SELECT hardate, crop,yield,unit,comments FROM harvested where hardate BETWEEN '".$year.
         "-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and harvested.crop like '".
         $crop."' and fieldID like '".$fieldID."' order by hardate";
      $sqldata = $dbcon->query($sql);
      $unit='';
      while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
         $totyield += $row['yield'];
         echo "<tr><td>";
         echo $row['hardate'];
         echo "</td><td>";
         echo $row['crop'];
         echo "</td><td>";
         echo $unit = $row['unit'];
         echo "</td><td>";
         echo number_format((float) $row['yield'], 2, '.', '');
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
         echo "\n";
      }
      if ($farm != 'wahlst_spiralpath') {
         $total="Select sum(yield) as total from harvested where hardate between '".$year."-".$month."-".
           $day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and harvested.crop like '" .$crop.
          "' and harvested.fieldID like '".$fieldID."'";
         try {
            $res=$dbcon->query($total);
         } catch (PDOException $p) {
            phpAlert('', $p);
         }
         $rt = $res->fetch(PDO::FETCH_ASSOC);
         $total = $rt['total'];
         $rowsql = "select sum(bedft * rowsBed) as rowft ".
                   "from dir_planted ".
                   "where crop = '".$crop."' and fieldID = '".$fieldID."' and exists".
                   "(select * from harvested where dir_planted.fieldID = harvested.fieldID and ".
                   "  dir_planted.gen = harvested.gen and harvested.crop = dir_planted.crop and ".
                   "  hardate between plantdate and lastHarvest and hardate between '".
                   $year."-".$month."-".$day."' and '".$tcurYear."-".$tcurMonth."-".$tcurDay."')";
         try {
            $rr=$dbcon->query($rowsql);
         } catch (PDOException $p) {
            phpAlert('', $p);
         }
         if ($rft = $rr->fetch(PDO::FETCH_ASSOC)) {
            $rowft = $rft['rowft'];
         } else {
            $rowft = 0;
         }

         $rowtsql = "select sum(bedft * rowsBed) as rowft ".
                    "from transferred_to ".
                    "where crop = '".$crop."' and fieldID = '".$fieldID."' and exists".
                    "(select * from harvested where transferred_to.fieldID = harvested.fieldID and ".
                    "  transferred_to.gen = harvested.gen and harvested.crop = transferred_to.crop and ".
                    " hardate between transdate and lastHarvest ".
//                    " year(hardate) = year(transdate) ".
                    " and hardate between '".
                    $year."-".$month."-".$day."' and '".$tcurYear."-".$tcurMonth."-".$tcurDay."')";
         try {
            $rrt=$dbcon->query($rowtsql);
         } catch (PDOException $p) {
            phpAlert('', $p);
         }
         if ($rftt = $rrt->fetch(PDO::FETCH_ASSOC)) {
            $rowftt = $rftt['rowft'];
         } else {
            $rowftt = 0;
         }
         $rowft += $rowftt;

         echo '<tr style="background:#ADD8E6"><td>Total Yield:</td><td>'.$crop.'</td><td>'.
            number_format((float) $total, 2, '.', '').' '.
            $unit.'(S)</td><td>'.number_format((float) ($total/$rowft), 2, '.', '').' '.$unit.
            '(S)/Row Foot</td><td>&nbsp;</td></tr>';

/*
         $sqlfeet = "select sum(rowft) as sumrowft from ".
           "(select bedft * rowsBed as rowft from dir_planted where crop like '".$crop.
           "' and fieldID like '".$fieldID."' and plantdate between '".$year."-01-01' AND '".
           $tcurYear."-".$tcurMonth."-".$tcurDay."' union all ".
           "select bedft * rowsBed as rowft from  transferred_to where ".
           "transdate between '".$year."-01-01' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
           "' and fieldID like '".$fieldID."' and crop like '".$crop."') as planted";
         $feetdata = $dbcon->query($sqlfeet);
         $feetrow = $feetdata->fetch(PDO::FETCH_ASSOC);
         $feet = $feetrow['sumrowft'];
         echo '<tr style="background:#ADD8E6"><td>Total Yield:</td><td>'.$crop.'</td><td>'.$totyield.' '.
            $unit.'</td><td>'.number_format((float) ($totyield/$feet), 2, '.', '').' '.$unit.
            '/Row Foot</td><td>&nbsp;</td></tr>';
*/
      }
   } while ($rowcrop = $cropdata->fetch(PDO::FETCH_ASSOC));
   echo "</table>";
}
echo '<br clear="all"/>';

if ($_SESSION['tillage']) {
   $sql = "Select tractorName, tilldate, tool, num_passes, comment,  percent_filled from tillage ".
      "where tilldate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."'  order by tilldate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>TILLAGE</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>TILLAGE</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
   
      echo "<thead><tr><th>Date</th><th>Tractor</th><th>Implement</th><th>% tilled</th><th>Passes</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';

if ($_SESSION['compost']) {
   $sql="select util_date,incorpTool,pileID,tperacre,incorpTiming,fieldSpread,comments from utilized_on where util_date between '"
      .$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."' order by util_date";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>COMPOST</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>COMPOST</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th> Date</th><th>Pile ID</th><th>Incorporation Tool</th><th>Incorporation Timing</th><th>Tons per Acre</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['irrigation']) {
   $sql = "select * from pump_field, pump_master where pump_field.id = pump_master.id and pumpDate between '".
      $year."-".$month."-".$day."' AND '".  $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".
     $fieldID."' order by pumpDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>IRRIGATION</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>IRRIGATION</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Irrigation Device</th><th>Hours</th><th>Comments</th></tr></thead>";
      do {
         echo "<tr><td>";
         echo $row['pumpDate'];
         echo "</td><td>";
         echo $row['irr_device'];
         echo "</td><td>";
         echo number_format(((float) $row['elapsed_time'])/60, 2, '.', '');
         echo "</td><td>";
         echo $row['comment'];
         echo "</td></tr>";
      } while($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['liquidfertilizer']) {
   $sql = "select * from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' order by inputDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>LIQUID FERTILIZER</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>LIQUID FERTILIZER</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Material</th><th>Total Material Applied</th><th>Unit</th><th>Drip Rows</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['dryfertilizer']) {
   $sql = "select inputDate, fertilizer, crops, rate, numBeds, totalApply, comments ".
      "from fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".
      $tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' order by inputDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>DRY FERTILIZER</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>DRY FERTILIZER</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Product</th><th>Application Rate<br>"."(lbs/acre)</th><th>Bed Feet</th><th>Total Applied</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}
if ($_SESSION['cover']) {
   /******   COVER CROP TABLE       */
   $sql = "SELECT id, seed_method,fieldID, ((Select size from field_GH where ".
      "fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool, comments, seedDate ".
      "FROM coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
   
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   
   $field = $_POST['fieldID'];
   if ($_POST['fieldID']=='%'){$field='All';}
   if (!$row) {
      echo '<h3>COVER CROP SEEDING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>COVER CROP SEEDING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th style='width:45%;'>Date</th> <th>FieldID</th> <th>Seeding Method</th> <th>Area Seeded</th><th>Incorporation Tool</th><th style='width: 60%;' >Crop</th><th>Comments</th></tr></thead>";
      do {
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
         $sql = "select * from coverSeed where id=".$row[id]." order by crop";
         $sqlCover = $dbcon->query($sql);
         $rowS = $sqlCover->fetch(PDO::FETCH_ASSOC);
         echo "<table class = 'pure-table pure-table-bordered'style='width:100%'>".
            "<thead><tr><th>Crop</th><th>Seeding Rate (lbs/acre)</th><th style='width: 30%;'>".
            "Pounds Seeded</th></tr></thead>";
         do {
            echo "<tr><td>".$rowS[crop]."</td><td>".$rowS[seedRate]."</td><td>".$rowS[num_pounds]."</td></tr>";
         } while ($rowS = $sqlCover->fetch(PDO::FETCH_ASSOC));
         echo "</table>";
         echo "</td><td>";
         echo $row['comments'];
         echo "</td></tr>";
         echo "\n";
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"\>';


   $sql="select killDate, seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
      totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
      FROM coverKill_master 
      WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND fieldID LIKE '".$fieldID."' 
      ORDER BY killDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);

   if (!$row) {
      echo '<h3>COVER CROP INCORPORATION</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>COVER CROP INCORPORATION</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Cover Crop</th><th>Seed Date</th><th>Incorporation Tool</th>".
         "<th>Total Biomass</th><th> Biomass Pounds Per Acre </th><th>Comments</th></tr></thead>";
      do {
         $var=number_format($row['bioPerAcre'],2,'.','');
         $countCrops = "select count(*) as num from coverKill where id=".$row['id'];
         $res = $dbcon->query($countCrops);
         $nr = $res->fetch(PDO::FETCH_ASSOC);
         $num = $nr['num'];
         $allCropsQuery = "SELECT coverCrop FROM coverKill WHERE id=".$row['id'];
         $cropResult = $dbcon->query($allCropsQuery);
         $cropString = "";
         $count = 1;
         while ($cropRow = $cropResult->fetch(PDO::FETCH_ASSOC)) {
            $cropString .= $cropRow['coverCrop'];
            if ($count < $num) {
               $cropString .= "</br>";
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      $sqlget = "Select sum(totalBiomass) as total, avg(totalBiomass) as average from coverKill_master ".
         "where killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and fieldID like '".$fieldID."'";
      $result = $dbcon->query($sqlget);
      while ($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
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
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>INSECT SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>INSECT SCOUTING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Crops</th><th>Insect</th><th>Average Count</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['weed']) {
   $sql="Select sDate,weed,infestLevel,goneToSeed,comments from weedScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."' order by sDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>WEED SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>WEED SCOUTING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Species</th><th>Infestation Level</th><th>% Gone to Seed</th>".
         "<th>Comment</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['disease']) {
   $sql="Select sDate,crops,disease,infest,stage,comments from diseaseScout where sDate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
      $fieldID."'";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>DISEASE SCOUTING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>DISEASE SCOUTING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Crops</th><th>Disease Species</th><th>Infestation Level</th>".
         "<th>Crop Stage</th><th>Comments</th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo "</table>";
   }
   echo '<br clear="all"/>';
}

if ($_SESSION['backspray']) {
   $sql = "Select sprayDate, materialSprayed, rate, BRateUnits, totalMaterial, crops, comments from ".
     "bspray, tSprayMaterials where sprayMaterial = materialSprayed ".
     "and sprayDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
     "' and fieldID like '".$fieldID."' order by sprayDate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$row) {
      echo '<h3>BACKPACK SPRAYING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>BACKPACK SPRAYING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo "<thead><tr><th>Date</th><th>Material Sprayed</th><th>Rate</th><th>Total Material</th>".
         "<th>Crops</th><th> Comments </th></tr></thead>";
      do {
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
      } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
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
   $sqldata = $dbcon->query($sql);
   $rowM = $sqldata->fetch(PDO::FETCH_ASSOC);
   if (!$rowM) {
      echo '<h3>TRACTOR SPRAYING</h3>No records this period.';
      echo '<br clear="all"/>';
   } else {
      echo "<h3>TRACTOR SPRAYING</h3>";
      echo "<table class = 'pure-table pure-table-bordered'>";
      echo '<thead><tr><th>Date</th><th>% Sprayed</th> <th>Material</th> <th>Rate</th> <th>Total Material</th>'.
         '<th>Crops</th> <th>Comments</th> </tr></thead>';
      do {
         $theUnit=$rowM['tRateUnits'];
         echo "<tr><td>".$rowM['sprayDate']."</td><td>".
            number_format($rowM['percentSprayed']*100, 2, '.','')."%"."</td><td>".$rowM['material'].
           "</td><td>".$rowM['rate']." ".$theUnit."/Acre</td><td>".
           number_format($rowM['frac'],2,'.','')." ".$theUnit."</td><td>".$rowM['crops']."</td><td>".
           $rowM['comment']."</td></tr>";
      } while ($rowM = $sqldata->fetch(PDO::FETCH_ASSOC));
      echo '</table>';
   }
   echo '<br clear="all"/>';
}
}

if ($_SESSION['labor']) {
$sql = "SELECT hours, ldate,crop,task,comments FROM laborview where ldate BETWEEN '".$year."-".$month.
   "-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".$fieldID.
   "' and hours > 0 order by crop, ldate";
   $sqldata = $dbcon->query($sql);
   $row = $sqldata->fetch(PDO::FETCH_ASSOC);
if (!$row) {
   echo '<h3>LABOR</h3>No records this period.';
   echo '<br clear="all"/>';
} else {
   echo "<h3>LABOR</h3>";
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Date</th><th>Crop</th><th>Task</th><th>Hours</th><th>Comments </th></tr></thead>";
   $hours=0;
   do {
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
   } while ($row = $sqldata->fetch(PDO::FETCH_ASSOC));
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
