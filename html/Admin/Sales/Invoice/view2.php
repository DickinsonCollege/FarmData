<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

if(isset($_GET['invoice'])){
   $invoiceID=$_GET['invoice'];
   $total=escapehtml($_GET['total']);
   $invoiceDate=$_GET['salesDate'];
   $target=escapehtml($_GET['target']);
   $invoiceIDNum=escapehtml($_GET['invoiceID']);
} 

$sql = "select farmname, farmemail, sig from config";
$res = mysql_query($sql);
echo mysql_error();
$row = mysql_fetch_array($res);
$farm = $row['farmname'];
$farmemail = $row['farmemail'];
$sig = $row['sig'];

$sql="Select product,cases,(Select units from plant where crop=invoice_entry.product union select unit from product where product.product=invoice_entry.product) as unit,(Select units_per_case from plant where crop=invoice_entry.product union select units_per_case from product where product.product=invoice_entry.product) as units_per_case,cases*(select units_per_case) as totalUnits,price_case,price_case*cases as total, (select salesDate from invoice_master where invoice_no=".$invoiceID.") as salesDate from invoice_entry where invoice_no=".$invoiceID;
$result2=mysql_query($sql);
echo mysql_error();
?>
<?php
$str= "<table border>".
"<caption>".$farm." Invoice # ".$invoiceIDNum.
 "<br>Customer:&nbsp;".$target."</caption>".
"<tr><th>Product</th><th><center>Cases</center></th><th>Unit</th><th>Units/
Case</th><th>Total Units</th><th><center>Price/ Case</center></th><th>Total</
th></tr>";

while ($row= mysql_fetch_array($result2)) {

	$dec2=number_format($row['total'],2,'.','');
	$dec=number_format($row['price_case'],2,'.','');
	$str=$str.
	 "<tr><td>".
        $row['product'].
        "</td><td>".
         $row['cases'].
        "</td><td>".
        $row['unit'].
        "</td><td>".
       $row['units_per_case'].
        "</td><td>".
        $row['totalUnits'].
        "</td><td>".
        $dec.
        "</td><td>".
        $dec2.
        "</td></tr>";

}

$str=$str. "</table>".'<br clear="all"/>';
$str=$str.'<label for="comment">Notes</label>';
$str=$str.'<br clear="all"/>';
$str=$str."<textarea name=\"comments\" rows=\"8\" col=\"70\" class='mobile-comments'>";
$sqlGetValue="SELECT comments from invoice_master where invoice_no=".$invoiceID;
$row2=mysql_fetch_array( mysql_query($sqlGetValue));
$str=$str.$row2['comments'];
$str=$str.'</textarea>';
$str=$str.'<br clear="all"/>';
$str=$str.'<br clear="all"/>';

$str=$str."<label for='total'> Total for Invoice: $".number_format($total,2,'.','')."<br> Date:&nbsp;".$_GET['salesDate']."</label>";
$str=$str.'<br clear="all"/>';
$str=$str.'<br clear="all"/>';
$str.=str_replace("\n", "<br>", $sig);
?>
<?php
echo $str;
?>
<script>
function approve() {
	var person=prompt("Invoice Approved by:");
	if (person!=null && person!="") {
		var invoice="<?php echo $invoiceID; ?>";
		var salesDate="<?php echo $invoiceDate;?>";
	//	console.log(salesDate);
		xmlhttp= new XMLHttpRequest();
		xmlhttp.open("GET","approve.php?by="+person+"&invoice="+invoice+"&salesDate="+salesDate,false);
		xmlhttp.send();
//	console.log(xmlhttp.responseText);
	return true;
	}else {
		alert("Name not valid!"); 
	//	console.log(false);
		return false;

	}
}
</script>
<form name='form' method='POST' id='send'>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit" value="Send" class="submitbutton" onClick="return approve();">
<?php
if(isset($_POST['submit'])){

  $sqlE="SELECT username from email union select email as username from ".
    " targetEmail where target = '".$target."'";
   $resultE=mysql_query($sqlE);
   $to='';
   while($rowE=mysql_fetch_array($resultE)){
	if($to==''){
	$to=$rowE['username'];
	}else{
	$to=$to.",".$rowE['username'];
	}
   }

   $subject = "Invoice # ".$invoiceIDNum;
   $sqlApproved="SELECT approved_by FROM invoice_master where invoice_no=". $_GET['invoice'];
   $resultA=mysql_query($sqlApproved);
   $rowA=mysql_fetch_array($resultA);
   $person=$rowA['approved_by'];

   $str=$str."<br><br> Approved by:&nbsp; ".$person;

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

// More headers
$headers .= 'From: <'.$farmemail.'>' . "\r\n";

mail($to,$subject,file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailDesign.php').$str,$headers);
echo '<script type="text/javascript">alert("Mail Sent!");</script>';
}
?>
</body>
</html>


