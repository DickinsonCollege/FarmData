<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$listYear=$_GET['year'];
$listMonth=$_GET['month'];
$listDay=$_GET['day'];
$listDate=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
$currentID=$_GET['currentID'];
$invoiceID=$_GET['invoiceID'];
$target=$_GET['target'];

?>
<script type="text/javascript">
function addToDistribution() {
  var id = <?php echo $currentID;?>;
}
</script>
<?php
echo '<center><h2>Date:&nbsp;'.$listDate.'<br> Customer:&nbsp;'.$target.
   '<br> Invoice #:&nbsp;'.$invoiceID.'</h2></center>';
?>
<div class='pure-form pure-form-aligned'>
<table class="pure-table pure-table-borded">
<thead><tr> 
   <th>Crop/Product</th>
   <th>Cases</th>
   <th>Price per Case</th>
   <th> </th>
</tr></thead>


<?php
if($_GET['deleteProduct']){
   $sqlD2="DELETE from invoice_entry where product='".
      $_GET['deleteProduct']."' and invoice_no=".$currentID;
   try {
      $stmt = $dbcon->prepare($sqlD2);
      $stmt->execute();
   } catch (PDOException $p) {
     phpAlert("Could not delete invoice entry", $p);
     die();
   }
}
?>

<script>
function checkIfOnList(){
   var id=<?php echo $currentID; ?>;
   var eValue= document.getElementById('CP').value;
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "invoiceCheckInList.php?product=" +
     encodeURIComponent(eValue) + "&currentID=" + id +
     "&target=" + encodeURIComponent(<?php echo "'".$target."'";?>),
      false);
   xmlhttp.send();
   var responseVar=xmlhttp.responseText;
   var c =document.getElementById('cases');
   var p =document.getElementById('pricePerCase');
   if (responseVar){        
       var item=responseVar.split(',');
       c.value=item[0];
       p.value=item[1];
   } else {
       c.value=0;
       p.value=0;
   }
}
</script>
</div>

<?php
echo "<form name='sendValue' class='pure-form pure-form-aligned' method='POST' action='".
   $_SERVER['PHP_SELF']."?year=".$listYear.
  "&month=".$listMonth."&day=".$listDay."&currentID=".$currentID."&invoiceID=".$invoiceID.
  "&target=".encodeURIComponent($target)."&tab=admin:admin_sales:invoice:editinvoice'>";
?>

<tr>
<td> 
<div class="styled-select">
<select name="CP" id="CP" onChange="checkIfOnList();"> 
<!--
<option value=1  selected> Crop/Product </option>
-->
<?php

$sql="SELECT crop from (select crop FROM plant where active=1 union ".
     "SELECT product as crop FROM product where active=1) as tmp order by crop";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"".$row['crop']."\">".$row['crop']."</option>";
}
?>
</select>
</div>
</td>

<td> <input type="text" name="cases" id="cases" size="3" class="wide"> </td>
<td> <input type="text" name="pricePerCase" id="pricePerCase"  size="2" class="wide"> </td>

<td>
<script type="text/javascript">
window.onload=function() {checkIfOnList();}
</script>
<input type="submit" name="submit" value="Submit" class="genericbutton pure-button wide">
</td>
</tr>
</table>
</form>

<?php
if(isset($_POST['submit'])){
   $cases = doubleval(escapehtml($_POST['cases']));
   $pricePerCase = doubleval(escapehtml($_POST['pricePerCase']));
   $CP = escapehtml($_POST['CP']);
   if (!empty($CP) && $cases>0 && $pricePerCase >0){
      $sqlD="DELETE from invoice_entry where product='".$CP."' and invoice_no=".$currentID;
      try {
         $stmt = $dbcon->prepare($sqlD);
         $stmt->execute();
      } catch (PDOException $p) {
        phpAlert("Could not delete invoice entry", $p);
        die();
      }
      $sql="INSERT INTO invoice_entry VALUES(".$currentID.", '".$CP."',".$cases.",".$pricePerCase.")";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
        phpAlert("Could not add invoice entry", $p);
        die();
      }
   } else {
      echo  "<script>alert(\"PLEASE enter all data\");</script> \n";
   }
}
?>

<?php
   include 'tableInvoice.php';
?>

<br clear="all"/>
<form name='comment' method='POST'>
<!--
<label for="comment">Add Notes</label>
<br clear="all"/>
-->
<h2>Add Notes</h2>
<?php
echo "<textarea name=\"comments\" rows=\"5\" col=\"30\" class='mobile-comments'>";
$sqlGetValue="SELECT comments from invoice_master where invoice_no=".$currentID;

if(isset($_POST['submit_notes'])){
   $comSanitized=escapehtml($_POST['comments']);
   $sql="UPDATE invoice_master SET comments='".$comSanitized."' where invoice_no=".$currentID;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
     phpAlert("Could not update comments", $p);
     die();
   }
}
$res2 = $dbcon->query($sqlGetValue);
$row2 = $res2->fetch(PDO::FETCH_ASSOC);
echo $row2['comments'];
?>
</textarea>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit_notes" class = "submitbutton pure-button wide" value="Update Notes" >
<!--
<br clear="all"/>
<br clear="all"/>
<input type="button" name="dist" class = "submitbutton" 
 value="Add Distribution Records for Current Invoice" 
 onclick="addToDistribution();">
-->
</form>


<!--
//   echo ' <meta http-equiv="refresh" content=10;URL="invoiceEntry.php?year='.$listYear.'&month='.$lisMonth.'&day='.$listDay.'&currentID='.$currentID.'&deleteProduct='.$deleteProduct.'" >';
-->


</body>
</html>

