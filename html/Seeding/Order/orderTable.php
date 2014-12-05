<?php session_start();?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
$year = $_POST['year'];
$crop = escapehtml($_POST['crop']);
$source = escapehtml($_POST['source']);
$status = $_POST['status'];
$order = $_POST['order'];

$sql="select orderItem.crop, variety, year, source, catalogOrder, case when organic = 1 then 'OG' else 'UT' end"
   ." as organic, catalogUnit, price, defUnit, ".
   "unitsPerCatUnit, catUnitsOrdered, unitsPerCatUnit * catUnitsOrdered as defUnitsOrdered, ".
   "catUnitsOrdered * price as totalPrice, status, source1, sdate1, source2, sdate2, source3, sdate3 ".
   " from orderItem, seedInfo where orderItem.crop = seedInfo.crop and year like ".$year.
  " and orderItem.crop like '".$crop."' and source like '".$source."' and status like '".$status."'";
if ($order == 'crop') {
   $sql .= " order by orderItem.crop, year, variety, source";
} else {
   $sql .= " order by organic, orderItem.crop, year, variety, source";
}
// echo $sql;
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
$result=mysql_query($sql);
if (!$result) {
        echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
}
echo "<table>";
echo "<caption>Seed Order Report for ";
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

echo "</caption>";

echo "<tr><th>Crop<center></th><th>Variety</th><th>Year</th><th>Source</th><th>Catalog Order #</th>";
echo "<th>Organic?</th><th>Catalog Unit</th><th>Price Per Catalog Unit</th><th>Default Unit</th>";
echo "<th>Default Units Per Catalog Unit</th><th>Number of Catalog Units Ordered</th>";
echo "<th>Default Units Ordered</th><th>Total Price</th><th>Order Status</th>";
echo "<th>Search Source 1</th><th>Date Searched 1</th>";
echo "<th>Search Source 2</th><th>Date Searched 2</th>";
echo "<th>Search Source 3</th><th>Date Searched 3</th></tr>";
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
     echo $defUnit = $row['defUnit'];
     echo "</td><td>";
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
echo "<label for='totPrice'>Total Price:&nbsp;</label>";
echo "<input style='width: 100px;' class='textbox2'type ='text' name='totPrice' disabled value='$".
  number_format((float) $totPrice, 2, '.','')."'>";
echo '<br clear="all"/>';
echo "<label for='org'>Organic:&nbsp;</label>";
echo "<input style='width: 100px;' class='textbox2'type ='text' name='org' disabled value='".
  number_format((float) ($org * 100/$count), 2, '.','')."%'>";
echo '<br clear="all"/>';
echo "<label for='norg'>Non-Organic:&nbsp;</label>";
echo "<input style='width: 100px;' class='textbox2'type ='text' name='norg' disabled value='".
  number_format((float) ($ut * 100/$count), 2, '.','')."%'>";
echo '<br clear="all"/>';
if ($crop != "%" && $defUnit != "") {
echo "<label for='quant'>Quantity:&nbsp;</label>";
echo "<input style='width: 100px;' class='textbox2'type ='text' name='quant' disabled value='".
  number_format((float) $totUnits, 2, '.','')."'>";
echo "<label>&nbsp;".$defUnit."</label>";
echo '<br clear="all"/>';
}

echo '<br clear="all"/>';
echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo '</form>';
echo '<form method="POST" action = "/Seeding/Order/orderReport.php?tab=seeding:ordert:ordert_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
