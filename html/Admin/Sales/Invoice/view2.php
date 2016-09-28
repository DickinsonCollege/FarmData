<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include 'Mail.php';
include 'Mail/mime.php';

if(isset($_GET['invoice'])){
   $invoiceID=$_GET['invoice'];
   $total=$_GET['total'];
   $invoiceDate=$_GET['salesDate'];
   $target=$_GET['target'];
   $invoiceIDNum=$_GET['invoiceID'];
} 

$sql = "select farmname, farmemail, sig from config";
$res = $dbcon->query($sql);
$row = $res->fetch(PDO::FETCH_ASSOC);
$farm = $row['farmname'];
$farmemail = $row['farmemail'];
$sig = $row['sig'];

$sql="Select product,cases,(Select units from plant where crop=invoice_entry.product union ".
   "select unit from product where product.product=invoice_entry.product) as unit, ".
   "(Select units_per_case from plant where crop=invoice_entry.product union select units_per_case ".
   "from product where product.product=invoice_entry.product) as units_per_case, ".
   "cases*(select units_per_case) as totalUnits,price_case,price_case*cases as total, ".
   "(select salesDate from invoice_master where invoice_no=".$invoiceID.") as salesDate ".
   "from invoice_entry where invoice_no=".$invoiceID;
$result2 = $dbcon->query($sql);
?>
<?php
$str= "<center><h2>".$farm." Invoice # ".$invoiceIDNum.
 "<br>Customer:&nbsp;".$target.
  "<br> Date:&nbsp;".$_GET['salesDate'].
  "</h2></center>".
"<table class='pure-table pure-table-bordered'>".
"<thead><tr><th>Product</th><th><center>Cases</center></th><th>Unit</th><th>Units per
Case</th><th>Total Units</th><th><center>Price per Case</center></th><th>Total</th></tr></thead>";

while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {

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
   "</td><td align='right'>".
   $dec.
   "</td><td align='right'>".
   $dec2.
   "</td></tr>";

}

$str=$str."<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td align='right'>Total:</td><td align='right'>".number_format($total,2,'.','')."</td></tr>";
$str=$str."</table>";
$str=$str.'<br clear="all"/>';
$str=$str.'<div class="pure-form pure-form-aligned"><div class="pure-control-group"><label>Notes:</label> ';
$str=$str."<textarea name=\"comments\" rows=\"8\" col=\"70\" class='mobile-comments'>";
$sqlGetValue="SELECT comments from invoice_master where invoice_no=".$invoiceID;
$res2 = $dbcon->query($sqlGetValue);
$row2 = $res2->fetch(PDO::FETCH_ASSOC);
$str=$str.$row2['comments'];
$str=$str.'</textarea></div></div>';
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
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET","approve.php?by="+person+"&invoice="+invoice+"&salesDate="+salesDate,false);
      xmlhttp.send();
      return true;
   } else {
      alert("Name not valid!"); 
      return false;

   }
}
</script>
<form name='form' method='POST' id='send'>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit" value="Send" class="submitbutton pure-button wide" onClick="return approve();">
<?php
if(isset($_POST['submit'])){

  $sqlE="SELECT username from email union select email as username from ".
    " targetEmail where target = '".$target."'";
   $resultE = $dbcon->query($sqlE);
   $to='';
   while($rowE = $resultE->fetch(PDO::FETCH_ASSOC)){
      if($to==''){
         $to=$rowE['username'];
      }else{
         $to=$to.",".$rowE['username'];
      }
   }

   $subject = "Invoice # ".$invoiceIDNum;
   $sqlApproved="SELECT approved_by FROM invoice_master where invoice_no=". $_GET['invoice'];
   $resultA = $dbcon->query($sqlApproved);
   $rowA = $resultA->fetch(PDO::FETCH_ASSOC);
   $person=$rowA['approved_by'];

   $str=$str."<br><br> Approved by:&nbsp; ".$person;

// Always set content-type when sending HTML email
//$headers = "MIME-Version: 1.0" . "\r\n";
//$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

   // More headers
   $headers .= 'From: <'.$farmemail.'>' . "\r\n";
   $headers = array (
      'From'   => $farmemail,
      'Return-Path'  => $farmemail,
      'Subject' => $subject,
      'To' => $to
   );
   $crlf = "\n";
   $mime = new Mail_mime($crlf);
   $mime->setHTMLBody(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailDesign.php').$str);
   $body = $mime->get();
   $headers = $mime->headers($headers);
   $mail =& Mail::factory('mail');
   $mail->send($to, $headers, $body);
   // mail($to,$subject,file_get_contents($_SERVER['DOCUMENT_ROOT'].'/emailDesign.php').$str,$headers);
   echo '<script type="text/javascript">alert("Mail Sent!");</script>';
}
?>
</body>
</html>


