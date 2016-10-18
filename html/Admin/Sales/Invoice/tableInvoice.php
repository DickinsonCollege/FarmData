<?php

$sql="Select product,cases,(Select dh_units from plant where crop=invoice_entry.product union ".
     "select dh_units from product where product.product=invoice_entry.product) as unit, ".
     "(Select units_per_case from plant where crop=invoice_entry.product union select units_per_case ".
     "from product where product.product=invoice_entry.product) as units_per_case, ".
     "cases*(select units_per_case) as total_units, price_case,price_case*cases as total_price ".
     "from invoice_entry where invoice_no=".$currentID;
$result2 = $dbcon->query($sql);
//echo '<br clear="all"/>';
echo "<h3>Current Invoice</h3>";
echo "<table class='pure-table pure-table-bordered'>";
# List date: ".$listDate;
echo "<thead><tr><th>Product</th><th><center>Cases</center></th><th>Unit</th><th>Units per Case</th><th>Total Units</th><th><center>Price per Case</center></th><th>Total Price</th><th>Delete</td></tr></thead>";
while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
   echo "<tr><td>";
   echo $row['product'];
   echo "</td><td>";
   echo $row['cases'];
   echo "</td><td>";
   echo $row['unit'];
   echo "</td><td>";
   echo $row['units_per_case'];
   echo "</td><td>";
   echo $row['total_units'];
   echo "</td><td align='right'>";
   echo "$".number_format((float) $row['price_case'], 2, '.', '');
   echo "</td><td align='right'>";
   echo "$".number_format((float) $row['total_price'], 2, '.', '');
   echo "<td><form method=\"POST\" action=\"invoiceEntry.php?year=".$listYear."&month=".$listMonth."&day=".
      $listDay."&currentID=".$currentID."&deleteProduct=".encodeURIComponent($row['product']).
      '&target='.encodeURIComponent($target).
      '&invoiceID='.$invoiceID.
      "&tab=".$_GET['tab']."\">";
   echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"></form> </td>";

}

echo "</table>";

echo '<br clear = "all">';echo "<div class='pure-g'>";
echo "<div class='pure-u-1-1'>";
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Invoice">';echo "</form>";
echo "</div>";
echo "</div>";
?>

