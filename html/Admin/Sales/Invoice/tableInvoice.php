<?php

$sql="Select product,cases,(Select dh_units from plant where crop=invoice_entry.product union select dh_units from product where product.product=invoice_entry.product) as unit,(Select units_per_case from plant where crop=invoice_entry.product union select units_per_case from product where product.product=invoice_entry.product) as units_per_case,cases*(select units_per_case) as totalUnits,price_case,price_case*cases as total from invoice_entry where invoice_no=".$currentID;
$result2=mysql_query($sql);
echo mysql_error();
//echo '<br clear="all"/>';
echo "<h3>Current Invoice</h3>";
echo "<table class='pure-table pure-table-bordered'>";
# List date: ".$listDate;
echo "<thead><tr><th>Product</th><th><center>Cases</center></th><th>Unit</th><th>Units per Case</th><th>Total Units</th><th><center>Price per Case</center></th><th>Total Price</th><th>Delete</td></tr></thead>";
while ($row= mysql_fetch_array($result2)) {
   // $deleteProduct=$row['product'];
   echo "<tr><td>";
   echo $row['product'];
   echo "</td><td>";
   echo $row['cases'];
   echo "</td><td>";
   echo $row['unit'];
   echo "</td><td>";
   echo $row['units_per_case'];
   echo "</td><td>";
   echo $row['totalUnits'];
   echo "</td><td align='right'>";
   echo "$".number_format((float) $row['price_case'], 2, '.', '');
   echo "</td><td align='right'>";
   echo "$".number_format((float) $row['total'], 2, '.', '');
   echo "<td><form method=\"POST\" action=\"invoiceEntry.php?year=".$listYear."&month=".$listMonth."&day=".
      $listDay."&currentID=".$currentID."&deleteProduct=".encodeURIComponent($row['product']).
      '&target='.encodeURIComponent($target).
      '&invoiceID='.$invoiceID.
      "&tab=".$_GET['tab']."\">";
   echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"></form> </td>";

}

echo "</table>";

?>

