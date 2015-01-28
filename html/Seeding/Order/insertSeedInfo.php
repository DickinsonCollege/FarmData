<?php session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_POST['crop']);
$year = $_POST['year'];
if ($_POST['isCover'] == "false") {
   $isCover = false;
} else {
   $isCover = true;
   $cover = $crop;
}

function convertFromGram($amt, $unit) {
   if ($unit == "GRAM") {
      $res = $amt;
   } else if ($unit == "OUNCE") {
      $res = $amt / 28.3495;
   } else if ($unit == "POUND") {
      $res = $amt / (28.3495 * 16);
   } else {
      $res = 0;
   }
   return $res;
}

function convert_units($from, $to) {
   if ($from == 'GRAM') {
      if ($to == 'GRAM') {
         $res = 1;
      } else if ($to == 'OUNCE') {
         $res = 0.035274;
      } else if ($to == 'POUND') {
         $res = 0.00220462;
      } else {
         $res = 0;
      }
   } else if ($from == 'OUNCE') {
      if ($to == 'GRAM') {
         $res = 28.3495;
      } else if ($to == 'OUNCE') {
         $res = 1;
      } else if ($to == 'POUND') {
         $res = 1.0 / 16.0;
      } else {
         $res = 0;
      }
   } else if ($from == 'POUND') {
      if ($to == 'GRAM') {
         $res = 453.592;
      } else if ($to == 'OUNCE') {
         $res = 16;
      } else if ($to == 'POUND') {
         $res = 1;
      } else {
         $res = 0;
      }
   } else {
      $res = 0;
   }
   return $res;
}

if (!$isCover) {
   $seedsIn = $_POST['seedsIn'];
   $rowft = $_POST['rowft'];
   $rowftToPlant = $_POST['rowftToPlant'];
   $defUnit = $_POST['defUnit'];
   $sql = "select * from seedInfo where crop = '".$crop."'";
   $res = mysql_query($sql);
   echo mysql_error();
   if (mysql_num_rows($res) == 0) {
      $sql = "insert into seedInfo values('".$crop."', 0, 0, 'GRAM')";
      $res = mysql_query($sql);
      echo mysql_error();
      $oldDefUnit = "";
   } else {
       $row = mysql_fetch_array($res);
       $oldDefUnit = $row['defUnit'];
   }
   if ($defUnit != $oldDefUnit) {
      $convert = convert_units($oldDefUnit, $defUnit);
      $sql = "update orderItem set unitsPerCatUnit = unitsPerCatUnit * ".
        $convert." where crop = '".$crop."'";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   if (isset($seedsIn)) {
      $seeds = convertFromGram($seedsIn, $defUnit);
      $sql = "update seedInfo set seedsGram = ".$seeds.", defUnit = '".$defUnit."' where crop = '".
         $crop."'";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   if (isset($rowft)) {
      $sql = "update seedInfo set seedsRowFt = ".$rowft." where crop = '".  $crop."'";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   if (isset($rowftToPlant) && $rowftToPlant != "") {
      $sql = "select * from toOrder where crop = '".$crop."' and year = ".$year;
      $res = mysql_query($sql);
      echo mysql_error();
      if (mysql_num_rows($res) == 0) {
         $sql = "insert into toOrder values('".$crop."', ".$year.", ".$rowftToPlant.", 1)";
         $res = mysql_query($sql);
         echo mysql_error();
      } else {
         $sql = "update toOrder set rowFt = ".$rowftToPlant." where crop = '".$crop."' and year = ".
            $year;
         $res = mysql_query($sql);
         echo mysql_error();
      }
   }
} else {
   $sql = "select * from coverSeedInfo where crop = '".$cover."'";
   $res = mysql_query($sql);
   echo mysql_error();
   if (mysql_num_rows($res) == 0) {
      $sql = "insert into coverSeedInfo values('".$cover."', 0)";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   $sql = "select * from coverToOrder where crop = '".$cover."' and year = ".$year;
   $res = mysql_query($sql);
   echo mysql_error();
   if (mysql_num_rows($res) == 0) {
      $sql = "insert into coverToOrder values('".$cover."', ".$year.", 0, 1)";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   $acres = escapehtml($_POST['acres']);
   $rate = escapehtml($_POST['rate']);
   if (isset($rate)) {
      $sql = "update coverSeedInfo set rate = ".$rate." where crop = '".  $cover."'";
      $res = mysql_query($sql);
      echo mysql_error();
   }
   if (isset($acres)) {
      $sql = "update coverToOrder set acres = ".$acres." where crop = '".  $cover."' and year = ".
         $year;;
      $res = mysql_query($sql);
      echo mysql_error();
   }
} 
echo '<meta http-equiv="refresh" content="0;URL=order.php?year='.$year.'&crop='.encodeURIComponent($crop).
   "&cover=".encodeURIComponent($cover)."&isCover=".$isCover.
   "&tab=seeding:ordert:ordert_input&calc_SEEDS=1000\">";
?>
