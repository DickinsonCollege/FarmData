<?php session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_POST['crop']);
$cover = $crop;
$year = $_POST['year'];
if ($_POST['isCover'] == "false") {
   $isCover = false;
} else if ($_POST['isCover'] == "true") {
   $isCover = true;
} else {
   die("Neither crop nor cover crop!");
}

$calc_SEEDS = $_POST['calc_SEEDS'];

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
   global $isCover;
   global $crop;
   global $cover;
   $rowNum = $_POST['invRows'];
   if ($isCover) {
      $sql = "delete from coverSeedInventory where crop = '".$cover."'";
      $inTable = "coverSeedInventory";
   } else {
      $sql = "delete from seedInventory where crop = '".$crop."'";
      $inTable = "seedInventory";
   }
   $res = mysql_query($sql);
   echo mysql_error();
   for ($i = 1; $i <= $rowNum; $i++) {
       if (isset($_POST['variety'.$i])) {
          $variety = escapehtml($_POST['variety'.$i]);
          $code = escapehtml(getString($_POST['code'.$i]));
          $rowft = getNum($_POST['rowft'.$i]);
          $inven = getNum($_POST['inven'.$i]);
          if (!$isCover) {
             $defUnit = escapehtml(getString($_POST['unit'.$i]));
             $inven = convertToGram($inven, $defUnit);
          }
          $sYear = getNum($_POST['sYear'.$i]);
          $sql = "insert into ".$inTable." values ('".$crop."', '".$variety."', ".$sYear.", '".
             $code."', ".$rowft.", ".$inven.")";
          $res = mysql_query($sql);
          echo mysql_error();
       }
   }
}

function insert_order_row($crop, $year, $i, $status) {
   global $isCover;
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
      $syear = $_POST['year'.$j.'_'.$i];
      $search['sdate'.$j] = $syear.'-'.$month.'-'.$day;
   }
   if ($isCover) {
     $tbl = "coverOrderItem";
   } else {
     $tbl = "orderItem";
   }
   $sql = "insert into ".$tbl." values('".$crop."', '".$variety."', ".$year.", '".$source.
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
   global $isCover;
   global $crop;
   global $cover;
   global $year;
   $rowNum = $_POST['orderRows'];
   if ($isCover) {
      $sql = "delete from coverOrderItem where crop = '".$cover."' and status <> 'ARRIVED' and year = ".
         $year;
   } else {
      $sql = "delete from orderItem where crop = '".$crop."' and status <> 'ARRIVED' and year = ".
         $year;
   }
   $res = mysql_query($sql);
   echo mysql_error();
   for ($i = 1; $i <= $rowNum; $i++) {
      if (isset($_POST['varOrder'.$i]) || isset($_POST['varSource'.$i])) {
         insert_order_row($crop, $year, $i, "");
      }
   }
}

function order_arrived($row) {
   global $isCover;
   global $crop;
   global $cover;
   $unitsPerCatUnit = getNum($_POST['unitsPerCatUnit'.$row]);
   $catUnitsOrdered = getNum($_POST['catUnitsOrdered'.$row]);
   $inven = $unitsPerCatUnit * $catUnitsOrdered;
   if (!$isCover) {
      $defUnit = $_POST['defUnit'];
      $inven = convertToGram($inven, $defUnit);
   }
   // $inven = $unitsPerCatUnit * $catUnitsOrdered;
   update_inventory();
   update_order();
   if ($isCover) {
      $ordcrop = $cover;
      $ordTbl = "coverOrderItem";
      $invTbl = "coverSeedInventory";
   } else {
      $ordcrop = $crop;
      $ordTbl = "orderItem";
      $invTbl = "seedInventory";
   }
   $sql = "update ".$ordTbl." set status = 'ARRIVED' where crop = '".$ordcrop.
      "' and status <> 'ARRIVED' and id = ".$row;
   $res = mysql_query($sql);
   echo mysql_error();
   $variety = escapehtml($_POST['varOrder'.$row]);
   $sYear = $_POST['year'];
   $org = $_POST['organic'.$row];
   include 'make_code.php';
   $sql = "insert into ".$invTbl." values ('".$crop."', '".$variety."', ".$sYear.", '".
      $code."', 0, ".$inven.")";
   $res = mysql_query($sql);
   echo mysql_error();
}

if (isset($_POST['updateInven']) || isset($_POST['update_order']) ||
    isset($_POST['submitAll'])) {
   update_inventory();
   update_order();
}
if (isset($_POST['addVar'])) {
   $variety = escapehtml($_POST['newVar']);
   if ($isCover) {
     $sql = "insert into coverVariety values('".$cover."', upper('".$variety."'))";
   } else {
     $sql = "insert into variety values('".$crop."', upper('".$variety."'))";
   }
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
if (isset($_POST['submitAll'])) {
  $page="chooseCrop";
} else {
  $page="order";
}
echo '<meta http-equiv="refresh" content="0;URL='.$page.'.php?year='.$year.'&crop='.encodeURIComponent($crop).
   "&cover=".encodeURIComponent($cover)."&isCover=".$isCover.
   "&tab=seeding:ordert:ordert_input&calc_SEEDS=".$calc_SEEDS."\">";
?>
