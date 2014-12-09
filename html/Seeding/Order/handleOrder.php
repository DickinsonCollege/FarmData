<?php session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = $_POST['crop'];
$year = $_POST['year'];
$calc_SEEDS = $_POST['calc_SEEDS'];

$units = array('GRAM', 'OUNCE', 'POUND');

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

function convertToGram($amt, $unit) {
   if ($unit == "GRAM") {
      $res = $amt;
   } else if ($unit == "OUNCE") {
      $res = $amt * 28.3495;
   } else if ($unit == "POUND") {
      $res = $amt * (28.3495 * 16);
   } else {
      $res = 0;
   }
   return $res;
}

function getString($str) {
   if (isset($str)) {
      return $str;
   } else {
      return "";
   }
}

function getNum($num) {
   if (isset($num) && $num != "" && is_numeric($num)) {
      return $num;
   } else {
      return 0;
   }
}

function update_inventory() {
   $crop = $_POST['crop'];
   $rowNum = $_POST['invRows'];
   $sql = "delete from seedInventory where crop = '".$crop."'";
   $res = mysql_query($sql);
   echo mysql_error();
   for ($i = 1; $i <= $rowNum; $i++) {
       if (isset($_POST['variety'.$i])) {
          $variety = escapehtml($_POST['variety'.$i]);
          $code = escapehtml(getString($_POST['code'.$i]));
          $rowft = getNum($_POST['rowft'.$i]);
          $defUnit = escapehtml(getString($_POST['unit'.$i]));
          $inven = getNum($_POST['inven'.$i]);
          $inven = convertToGram($inven, $defUnit);
          $sYear = getNum($_POST['sYear'.$i]);
          $sql = "insert into seedInventory values ('".$crop."', '".$variety."', ".$sYear.", '".
             $code."', ".$rowft.", ".$inven.")";
          $res = mysql_query($sql);
          echo mysql_error();
       }
   }
}

function insert_order_row($crop, $year, $i, $status) {
   $variety = escapehtml($_POST['varOrder'.$i]);
   $source = escapehtml($_POST['varSource'.$i]);
   $catalogOrder = escapehtml($_POST['catalogOrder'.$i]);
   $organic = $_POST['organic'.$i];
   $catalogUnit = escapehtml($_POST['catalogUnit'.$i]);
   $price = getNum($_POST['price'.$i]);
   $unitsPerCatUnit = getNum($_POST['unitsPerCatUnit'.$i]);
   $catUnitsOrdered = getNum($_POST['catUnitsOrdered'.$i]);
   $id = getNum($_POST['orderId'.$i]);
   if ($status == "") {
      $status = escapehtml($_POST['status'.$i]);
   }
   $search = array();
   for ($j = 1; $j < 4; $j++) {
      $search['source'.$j] = escapehtml($_POST['searchSource'.$j.'_'.$i]);
      $month = $_POST['month'.$j.'_'.$i];
      $day = $_POST['day'.$j.'_'.$i];
      $year = $_POST['year'.$j.'_'.$i];
      $search['sdate'.$j] = $year.'-'.$month.'-'.$day;
   }
   $sql = "insert into orderItem values('".$crop."', '".$variety."', ".$year.", '".$source.
      "', '".$catalogOrder."', ".$organic.", '".$catalogUnit."', ".$price.", ".$unitsPerCatUnit.
      ", ".$catUnitsOrdered.", '".$status."'";
   for ($j = 1; $j < 4; $j++) {
      $sql .= ", '".$search['source'.$j]."', '".$search['sdate'.$j]."'";
   }
   $sql .= ", ".$id.")";
   $res = mysql_query($sql);
   echo mysql_error();
}

function update_order() {
   $crop = $_POST['crop'];
   $year = $_POST['year'];
   $rowNum = $_POST['orderRows'];
   $sql = "delete from orderItem where crop = '".$crop."' and status <> 'ARRIVED'";
   $res = mysql_query($sql);
   echo mysql_error();
   for ($i = 1; $i <= $rowNum; $i++) {
      if (isset($_POST['varOrder'.$i]) || isset($_POST['varSource'.$i])) {
         insert_order_row($crop, $year, $i, "");
      }
   }
}

function order_arrived($row) {
   $unitsPerCatUnit = getNum($_POST['unitsPerCatUnit'.$row]);
   $catUnitsOrdered = getNum($_POST['catUnitsOrdered'.$row]);
   $inven = $unitsPerCatUnit * $catUnitsOrdered;
   $defUnit = $_POST['defUnit'];
   $inven = convertToGram($inven, $defUnit);
   update_inventory();
   update_order();
   $crop = $_POST['crop'];
   $sql = "update orderItem set status = 'ARRIVED' where crop = '".$crop.
      "' and status <> 'ARRIVED' and id = ".$row;
   $res = mysql_query($sql);
   echo mysql_error();
   $variety = escapehtml($_POST['varOrder'.$row]);
   $sYear = $_POST['year'];
   $org = $_POST['organic'.$i];
   include 'make_code.php';
   $sql = "insert into seedInventory values ('".$crop."', '".$variety."', ".$sYear.", '".
      $code."', 0, ".$inven.")";
   $res = mysql_query($sql);
   echo mysql_error();
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

if (isset($_POST['updateSeedInfo'])) {
  if (isset($_POST['crop'])) {
     $crop = $_POST['crop'];
     $year = $_POST['year'];
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
// and year = ".$year;
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
     echo '<script type="text/javascript">alert("Please select a crop!");</script>';
  }
} 
if (isset($_POST['updateInven'])) {
   update_inventory();
   update_order();
}
if (isset($_POST['update_order'])) {
   update_inventory();
   update_order();
}
if (isset($_POST['addVar'])) {
     $crop = $_POST['crop'];
     $variety = escapehtml($_POST['newVar']);
     $sql = "insert into variety values('".$crop."', upper('".$variety."'))";
     $res = mysql_query($sql);
     echo mysql_error();
     update_inventory();
     update_order();
} 
if (isset($_POST['addSource'])) {
     $source = escapehtml($_POST['newSource']);
     $sql = "insert into source values(upper('".$source."'))";
     $res = mysql_query($sql);
     echo mysql_error();
     update_inventory();
     update_order();
}
$rowNum = $_POST['orderRows'];
for ($i = 1; $i <= $rowNum; $i++) {
   if (isset($_POST['add_inventory'.$i])) {
      order_arrived($i);
   }
}
echo '<meta http-equiv="refresh" content="0;URL=order.php?year='.$year.'&crop='.encodeURIComponent($crop).
"&tab=seeding:ordert:ordert_input&calc_SEEDS=".$calc_SEEDS."\">";
?>
