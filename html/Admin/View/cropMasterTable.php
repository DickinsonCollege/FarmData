<?php session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$this_year = $_GET['year'];
$this_tyear = $_GET['tyear'];
$id = escapehtml($_GET["fieldID"]);

if ($id =="%") {
   $flds = array();
   echo '<center><h2>Crop History for All Fields</h2></center>';
   $result = $dbcon->query("Select distinct fieldID from field_GH");
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        array_push($flds, $row['fieldID']);
   } 
}
else {
   echo '<center><h2>Crop History for Field '.$id.'</h2></center>';
   $flds = array($id);
}

$bedft = array();
$acres = array();
for ($year = $this_year; $year <= $this_tyear; $year++) {
   foreach ($flds as $fieldID) {
      $direct_query = "Select plantdate, bedft, crop from dir_planted where fieldID = '".$fieldID.
         "' and plantdate between '".$year."-01-01' and '".$year."-12-31';";
      $direct_data = $dbcon->query($direct_query);

      $trans_query = "Select transdate, bedft, crop from transferred_to where fieldID = '".$fieldID.
         "' and transdate between '".$year."-01-01' and '".$year."-12-31';";
      $trans_data = $dbcon->query($trans_query);

      $cover_query = "Select id, ((Select size from field_GH where fieldID = coverSeed_master.fieldID)".
         "/100)*area_seeded as areaSeeded, seedDate from coverSeed_master where fieldID = '".
         $fieldID."' and seedDate between '".$year."-01-01' and '".$year."-12-31';";
      $cover_data = $dbcon->query($cover_query);

      //gather data from directly planted crop
      while ($res = $direct_data->fetch(PDO::FETCH_ASSOC)) {
         $curYear = date('Y', strtotime($res['plantdate'])); 
         $curCrop = $res['crop'];
         if (!$bedft[$curYear][$fieldID][$curCrop]) {
            $bedft[$curYear][$fieldID][$curCrop] = $res['bedft'];
         } else {
            $bedft[$curYear][$fieldID][$curCrop] += $res['bedft'];
         }
      }

      //gather data from transplanted crop
      while ($res = $trans_data->fetch(PDO::FETCH_ASSOC)) {
         $curYear = date('Y', strtotime($res['transdate'])); 
         $curCrop = $res['crop'];
         if (!$bedft[$curYear][$fieldID][$curCrop]) {
            $bedft[$curYear][$fieldID][$curCrop] = $res['bedft'];
         } else {
            $bedft[$curYear][$fieldID][$curCrop] += $res['bedft'];
         }
      }

      // gather cover crop data
      while ($res = $cover_data->fetch(PDO::FETCH_ASSOC)) {
         $curYear = date('Y', strtotime($res['seedDate']));
      
         $cropQuery = "Select id, crop from coverSeed where id = ".$res['id']." order by crop;";
         $crop_data = $dbcon->query($cropQuery);
         $curCrops = "";
         while ($crop_res = $crop_data->fetch(PDO::FETCH_ASSOC)) {
            $temp = $crop_res['crop'];
            $curCrops .= $temp."; "; 
         }
         $curCrops = trim($curCrops, " ;");
         $curCrops.= ": ".number_format((float) $res['areaSeeded'], 2, '.', '');
         if ($acres[$curYear][$fieldID]) {
            $acres[$curYear][$fieldID] .= "<br>".$curCrops;
         } else {
            $acres[$curYear][$fieldID] = $curCrops;
         }
      }
   }
}

$final = array();
if (count($bedft) == 0 && count($acres == 0)) {
   echo "No records for this period.";
   echo "<br clear = 'all'>";
   echo "<br clear = 'all'>";
} else {
   for ($year = $this_tyear; $year >= $this_year; $year--) {
      foreach ($flds as $fieldID) {
         $crps = "";
         if ($bedft[$year][$fieldID]) {
            foreach ($bedft[$year][$fieldID] as $crp => $ft) {
               $crps .= $crp.":&nbsp;".number_format((float) $ft, 1, '.', '')."<br>";
            }
         }
         if ($crps != "") {
            $crps = "<b>Crops&nbsp;(bed&nbsp;ft)</b><br>".$crps;
         }
         if ($acres[$year][$fieldID]) {
            $cov = "<b>Cover&nbsp;Crops&nbsp;(acres)</b><br>".$acres[$year][$fieldID];
            if ($crps == "") {
               $crps = $cov;
            } else {
               $crps.= $cov;
            }
         } 
         $final[$year][$fieldID] = $crps;
      }
   }
      
   echo "<table class = 'pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Year</th>";
   foreach ($flds as $fieldID) {
      echo "<th>".$fieldID."</th>";
   }
   echo "</tr></thead>";
   for ($year = $this_tyear; $year >= $this_year; $year--) {
      echo "<tr><td>".$year."</td>";
      foreach ($flds as $fieldID) {
         echo "<td>";
         if ($final[$year][$fieldID]) {
             echo $final[$year][$fieldID];
         } else {
             echo "&nbsp;";
         }
         echo "</td>";
         $crps = "";
      }
      echo "</tr>";
   }
   echo "</table><br clear='all'>";
}
?>
<div class = "pure-g">
<div class = "pure-u-1-2">
<form name = 'form' method = "POST" action = 'down.php'>
<?php
echo "<input type='hidden' name='fields' value='".htmlentities(serialize($flds))."'>";
echo "<input type='hidden' name='vals' value='".htmlentities(serialize($final))."'>";
echo "<input type='hidden' name='year' value='".$this_year."'>";
echo "<input type='hidden' name='tyear' value='".$this_tyear."'>";
?>
<input class = "submitbutton pure-button wide" type = "submit" name = "submit" value = "Download Report">
</form>
</div>

<div class = "pure-u-1-2">
<form  method = "POST" action = 'cropHistory.php?tab=admin:admin_view:view_tables:view_history'>
<input class = "submitbutton pure-button wide" type = "submit" name = "submit" value = "Run Another Report">
</form>
</div>
</div>
