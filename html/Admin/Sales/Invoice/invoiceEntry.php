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
<br clear="all"/>
<table >
<?php
echo '<caption>Date:&nbsp;'.$listDate.'&nbsp Customer:&nbsp;'.$target.
   '&nbsp; Invoice #:&nbsp;'.$invoiceID.'</caption>';
?>
<tr>    <th>Crop_Product:</th>
        <th>Cases:</th>
        <th>Price_case:</th>
	<th> </th>
</tr>


<?php
if($_GET['deleteProduct']){
   $sqlD2="DELETE from invoice_entry where product='".
      $_GET['deleteProduct']."' and invoice_no=".$currentID;
   mysql_query($sqlD2);
}
?>

<script>
        function checkIfOnList(){
       //	console.log(e);
	var id=<?php echo $currentID; ?>;
        var eValue= document.getElementById('CP').value;
//        console.log("HIHIHI");  
//      console.log(id);
        xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET", "invoiceCheckInList.php?product="+encodeURIComponent(eValue) +
           "&currentID="+id, false);
        xmlhttp.send();
        var responseVar=xmlhttp.responseText;
//        console.log(responseVar);
        var c =document.getElementById('cases');
        var p =document.getElementById('pricePerCase');
        if(responseVar){        
        var item=responseVar.split(',');
        c.value=item[0];
        p.value=item[1];
        }else{
        c.value=0;
        p.value=0;
        }
}
</script>



<form name='sendValue' method='POST'>



<tr>
<td> 
<div class="styled-select">
<select name="CP" id="CP" onChange="checkIfOnList();"> 
<option value=1  selected> Crop/Product </option>
<?php

$sql="SELECT crop FROM plant union SELECT product FROM product";
$result=mysql_query($sql);
echo mysql_error();
while($row=mysql_fetch_array($result)){
	echo "\n<option value= \"".$row['crop']."\">".$row['crop']."</option>";
}
?>
</select>
</div>
</td>

<td> <input type="text" name="cases" id="cases" placeholder="number of cases"> </td>
<td> $<input type="text" name="pricePerCase" id="pricePerCase" placeholder="price Per Case"> </td>

<td>
<input type="submit" name="submit" value="Submit" class="genericbutton">
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
      $sqlD="DELETE from invoice_entry where product='".$CP.
         "' and invoice_no=".$currentID;
        mysql_query($sqlD);	
        echo mysql_error();
        $sql="INSERT INTO invoice_entry VALUES(".$currentID.", '"
           .$CP."',".$cases.",".$pricePerCase.")";
        $result=mysql_query($sql);
        echo mysql_error();
        if(!$result){
           echo  "<script>alert(\"Error in data entry!\\n".mysql_error().
             "\");</script> \n";
        }
   } else {
      echo  "<script>alert(\"PLEASE enter all data\\n".mysql_error().
           "\");</script> \n";
   }
}
?>



<?php
	include 'tableInvoice.php';
?>

<br clear="all"/>
<form name='comment' method='POST'>
<label for="comment">Add Notes</label>
<br clear="all"/>
<?php
echo "<textarea name=\"comments\" rows=\"20\" col=\"30\" class='mobile-comments'>";
$sqlGetValue="SELECT comments from invoice_master where invoice_no=".$currentID;

if(isset($_POST['submit_notes'])){
$comSanitized=escapehtml($_POST['comments']);
$sql="UPDATE invoice_master SET comments='".$comSanitized."' where invoice_no=".$currentID;
mysql_query($sql);
echo mysql_error();
}
$row2=mysql_fetch_array( mysql_query($sqlGetValue));
echo $row2['comments'];
?>
</textarea>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit_notes" class = "submitbutton" value="Update Notes" >
</form>


<!--
//	echo ' <meta http-equiv="refresh" content=10;URL="invoiceEntry.php?year='.$listYear.'&month='.$lisMonth.'&day='.$listDay.'&currentID='.$currentID.'&deleteProduct='.$deleteProduct.'" >';
-->


</body>
</html>

