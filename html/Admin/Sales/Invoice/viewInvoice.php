<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<?php
if(isset($_GET['invoice'])){
   $id=$_GET['invoice'];
   $total=$_GET['total'];
   $target=$_GET['target'];
   $invoiceID=$_GET['invoiceID'];
} 

$sql = "select farmname from config";
$res = mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($res);
$farm = $row['farmname'];

$sql="Select product,cases,(Select units from plant where crop=invoice_entry.product union select unit from product where product.product=invoice_entry.product) as unit,(Select units_per_case from plant where crop=invoice_entry.product union select units_per_case from product where product.product= invoice_entry.product) as units_per_case,cases*(select units_per_case) as totalUnits,price_case,price_case*cases as total from invoice_entry where invoice_no=".$id;
$result2=mysql_query($sql);
echo '<br clear="all"/>';
echo "<table class = 'pure-table pure-table-bordered'>";
echo "<center><h2>".$farm." Invoice # ".$invoiceID."&nbsp;<br>Customer:&nbsp; ".
  $target."</h2></center>";
echo "<thead><tr><th>Product</th><th><center>Cases</center></th><th>Unit</th><th>Units per Case</th><th>Total Units</th><th><center>Price per Case</center></th><th>Total</th></tr></thead>";
while ($row= mysql_fetch_array($result2)) {
	$dec2=number_format($row['total'],2,'.','');
        $dec=number_format($row['price_case'],2,'.','');
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
        echo "$".$dec;
        echo "</td><td align='right'>";
        echo "$".$dec2;
        echo "</td></tr>";

}


echo "</table>\n\n";
echo '<br clear="all"/>';
echo '<div class = "pure-form pure-form-aligned">';
echo '<div class = "pure-control-group">';
echo '<label for="comment">Notes:</label> ';
echo "<textarea name=\"comments\" rows=\"5\" col=\"30\" class='mobile-comments'>";
$sqlGetValue="SELECT comments from invoice_master where invoice_no=".$id;
$row2=mysql_fetch_array( mysql_query($sqlGetValue));
echo $row2['comments'];
echo '</textarea>';
echo '</div>';

echo '<div class = "pure-control-group">';
echo "<label for='total'>Total for Invoice ".$invoiceID.":</label> ";
echo "<input type='text' readonly name='total' id='total' class='textbox3' value='$".number_format($total,2,'.','')."'>";
echo '</div>';

echo '<div class = "pure-control-group">';
echo "<label for='sdate'>Date:</label> ";
$sql2 = "select salesDate, approved_by from invoice_master where invoice_no = ".$id;
$result3=mysql_query($sql2);
while ($row= mysql_fetch_array($result3)) {
        $sDate = $row['salesDate'];
        $appBy = $row['approved_by'];
}
echo "<input type='text' readonly name='sdate' id='sdate' class='textbox3' value='".$sDate."'>\n";
echo '</div>';

echo '<div class = "pure-control-group">';
echo "<label for='app'>Approved By:</label> ";
echo "<input type='text' readonly name='app' id='app' class='textbox2' value='".$appBy."'><br>\n";
echo '</div>';
echo '</div>';
?>

