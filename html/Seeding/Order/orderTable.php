<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' class='pure-form pure-form-aligned' method='POST' action='/down.php'>
<?php
$year = $_POST['year'];
$crop = escapehtml($_POST['crop']);
$covercrop = escapehtml($_POST['covercrop']);
$source = escapehtml($_POST['source']);
$status = $_POST['status'];
$order = $_POST['order'];
if ($_POST['submitCrop']) {
  $itemTable = "orderItem";
  $infoTable = "seedInfo";
  $isCover = false;
} else {
  $itemTable = "coverOrderItem";
  $infoTable = "coverSeedInfo";
  $isCover = true;
}

$sql="select ".$itemTable.".crop, variety, year, source, catalogOrder, case when organic = 1 then 'OG' else 'UT' end"
   ." as organic, catalogUnit, price, ";
if (!$isCover) {
   $sql .= "defUnit, ";
}
$sql .= "unitsPerCatUnit, catUnitsOrdered, unitsPerCatUnit * catUnitsOrdered as defUnitsOrdered, ".
   "catUnitsOrdered * price as totalPrice, status, source1, sdate1, source2, sdate2, source3, sdate3 ".
   " from ".$itemTable.", ".$infoTable." where ".$itemTable.".crop = ".$infoTable.
   ".crop and year like '".$year.  "' and ".$itemTable.".crop like '".$crop."' and source like '".
   $source."' and status like '".$status."'";
if ($order == 'crop') {
   $sql .= " order by ".$itemTable.".crop, year, variety, source";
} else {
   $sql .= " order by organic, ".$itemTable.".crop, year, variety, source";
}
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
$result=mysql_query($sql);
if (!$result) {
        echo "<script>alert(\"Could not retrieve report: Please try again!\\n".mysql_error()."\");</script>\n";
}
echo "<center>";
echo "<h2>Seed Order Report for ";
if ($crop == "%") {
  echo "All Crops in ";
} else {
  echo $crop." in ";
}
if ($year == "%") {
  echo "All Years";
} else {
  echo $year;
}
echo " from ";
if ($source == "%") {
  echo "All Sources";
} else {
  echo $source;
}
echo " of Status: ";
if ($status == "%") {
  echo "All";
} else {
  echo $status;
}

echo "</h2>";
echo "</center>";

echo "<table class='pure-table pure-table-bordered'>";

echo "<thead><tr><th>Crop<center></th><th>Variety</th><th>Year</th><th>Source</th><th>Catalog Order #</th>";
echo "<th>Organic?</th><th>Catalog Unit</th><th>Price Per Catalog Unit</th>";
if (!$isCover) {
   echo "<th>Default Unit</th>";
}
echo "<th>";
if ($isCover) {
   echo "Pounds";
} else {
   echo "Default Units";
}
echo " Per Catalog Unit</th><th>Number of Catalog Units Ordered</th>";
echo "<th>";
if ($isCover) {
   echo "Pounds";
} else {
   echo "Default Units";
}
echo " Ordered</th><th>Total Price</th><th>Order Status</th>";
echo "<th>Search Source 1</th><th>Date Searched 1</th>";
echo "<th>Search Source 2</th><th>Date Searched 2</th>";
echo "<th>Search Source 3</th><th>Date Searched 3</th></tr></thead>";
$count = 0;
$totPrice = 0;
$totUnits = 0;
$org = 0;
$ut = 0;
$defUnit = "";
while ($row= mysql_fetch_array($result)) {
     $count++;
     echo "<tr><td>";
     echo $row['crop'];
     echo "</td><td>";
     echo $row['variety'];
     echo "</td><td>";
     echo $row['year'];
     echo "</td><td>";
     echo $row['source'];
     echo "</td><td>";
     echo $row['catalogOrder'];
     echo "</td><td>";
     echo $row['organic'];
     if ($row['organic'] == "OG") {
        $org++;
     } else {
        $ut++;
     }
     echo "</td><td>";
     echo $row['catalogUnit'];
     echo "</td><td>";
     echo number_format((float) $row['price'], 2, '.', '');
     echo "</td><td>";
     if (!$isCover) {
        echo $defUnit = $row['defUnit'];
        echo "</td><td>";
     }
     echo $row['unitsPerCatUnit'];
     echo "</td><td>";
     echo $row['catUnitsOrdered'];
     echo "</td><td>";
     echo $row['defUnitsOrdered'];
     $totUnits += $row['defUnitsOrdered'];
     echo "</td><td>";
     echo number_format((float) $row['totalPrice'], 2, '.', '');
     $totPrice += $row['totalPrice'];
     echo "</td><td>";
     echo $row['status'];
     echo "</td><td>";
     echo $row['source1'];
     echo "</td><td>";
     echo $row['sdate1'];
     echo "</td><td>";
     echo $row['source2'];
     echo "</td><td>";
     echo $row['sdate2'];
     echo "</td><td>";
     echo $row['source3'];
     echo "</td><td>";
     echo $row['sdate3'];
     echo "</td></tr>";
}
echo "</table>";
echo '<br clear="all"/>';
echo '<div class="pure-control-group">';
echo "<label for='totPrice'>Total Price:</label> ";
echo "<input type ='text' name='totPrice' readonly value='$".
  number_format((float) $totPrice, 2, '.','')."'>";
echo '</div>';
echo '<div class="pure-control-group">';
echo "<label for='org'>Organic:</label> ";
echo "<input type ='text' name='org' readonly value='".
  number_format((float) ($org * 100/$count), 2, '.','')."%'>";
echo '</div>';
echo '<div class="pure-control-group">';
echo "<label for='norg'>Non-Organic:</label> ";
echo "<input type ='text' name='norg' readonly value='".
  number_format((float) ($ut * 100/$count), 2, '.','')."%'>";
echo '</div>';
if ($crop != "%" && $defUnit != "") {
echo '<div class="pure-control-group">';
echo "<label for='quant'>Quantity:</label> ";
echo "<input type ='text' name='quant' disabled value='".
  number_format((float) $totUnits, 2, '.','')."'>";
// echo "<label>".$defUnit."</label>";
echo "&nbsp;".$defUnit."(S)";
echo '</div>';
}

echo '<br clear="all"/>';
echo '<div class="pure-g">';
echo '<div class="pure-u-1-2">';
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '</div>';
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "/Seeding/Order/orderReport.php?tab=seeding:ordert:ordert_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo '</div>';
echo '</div>';
?>
