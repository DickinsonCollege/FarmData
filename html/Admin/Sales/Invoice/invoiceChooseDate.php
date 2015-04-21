<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<form name='date' method='POST' action="<?php $_PHP_SELF ?>">
<script type="text/javascript">
function show_confirm() {
  return confirm("Confirm Deletion");
}
</script>
<?php
if($_GET['exist']==1 || $_POST['hidden']==1){
   echo "<h3> Edit Invoice </h3>";
   $exist = 1;
}else{
   echo "<h3>Create Invoice </h3>";
   $exist = 0;
}
echo "<br>";
?>
<label for="target">Sales Target:&nbsp;</label>
<div id='targDiv' class='styled-select'>
<select name="target" id="target">
<?php
if ($exist) {
   echo '<option value="%">All</option>';
} else {
   echo '<option value=0 selected disabled>Sales Target</option>';
}
$sql = "select targetName from targets where active = 1";
$result=mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)){
  $targ = $row1['targetName'];
  if ($targ != 'Loss') {
     echo '<option value= "'.escapeHTML($targ).'">'.$targ.'</option>';
  }
}
?>
</select>
<br clear="all"/>

<?php
if ($exist) {
  echo '<label for="date">From:&nbsp;</label>';
} else {
  echo '<label for="date">Invoice Date:&nbsp;</label>';
}
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
if ($exist) {
   echo '<br clear="all"/>';
   echo '<label for="date2">To:&nbsp;</label>';
   include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
}
echo '<br clear="all"/>';
echo '<br clear="all"/>';

if($exist){
   echo "<input type='hidden' name='hidden' value=".$_GET['exist'] .">";
}else{
   echo "<input type='hidden' name='hidden' value=".$_POST['hidden'] .">";
}
?>
<input class="submitbutton" type="submit" name="submit" value="Choose" >
</form>


<?php
if(isset($_POST['submit']) && isset($_POST['target'])){
   $day=$_POST['day'];
   $month=$_POST['month'];
   $year=$_POST['year'];
   $target=$_POST['target'];
   $exist=$_POST['hidden'];
   echo '<br clear="all"/>';
   $listDate=$year."-".$month."-".$day;
   echo '<br clear="all"/>';
	
   if($exist=="1"){
      $tcurYear = $_POST['tyear'];
      $tcurMonth = $_POST['tmonth'];
      $tcurDay = $_POST['tday'];

      $sql="SELECT invoice_id,invoice_no, salesDate,target FROM ".
         "invoice_master WHERE".
         " salesDate between '".$listDate."' and '".$tcurYear."-".$tcurMonth."-".$tcurDay.
         "' and target like '".$target."'";
      $sql_result=mysql_query($sql);
//        echo mysql_error();
      if(is_resource($sql_result) &&  mysql_num_rows($sql_result) > 0 ){
	 echo "<table border>";
	 echo "<tr><th>Sales Date</th><th><center>Invoice No.</center></th>".
            "<th>Customer</th><th>Edit</th><th>Delete</th></tr>";
        while ($row=mysql_fetch_array($sql_result)) {
           echo "<tr><td>";
           echo $row['salesDate'];
           echo "</td><td>";
           echo $row['invoice_id'];
           echo "</td><td>";
           echo $row['target'];
           echo "</td><td>";
            echo '<form method="POST" action="invoiceEntry.php?currentID='.
               $row['invoice_no'].
              '&month='.$month.'&day='.$day.'&year='.$year.
              '&target='.encodeURIComponent($row['target']).
              '&invoiceID='.$row['invoice_id'].
              '&tab=admin:admin_sales:invoice:editinvoice">';
           echo "<input type='submit' class='editbutton' value='Edit'></form>";
           echo "</td><td>";
           echo '<form method="POST" action="invoiceDelete.php?invoice='.
              $row['invoice_no'].
              '&tab=admin:admin_sales:invoice:editinvoice">';
           echo "<input type='submit' class='deletebutton' onclick='return show_confirm();' value='Delete'></form>";
	   echo "</td></tr>";
	}	
	echo "</table>";

      //  $currentID= $sql_result["invoice_no"];
      //  echo "<script>alert(\"Found an existing invoice\");</script> \n";
       // echo ' <meta http-equiv="refresh" content=0;URL="invoiceEntry.php?year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.' ">';
        }else{
        echo "<script>alert(\"Did not find an existing invoice, please create a new invoice\");</script> \n";
       	
	echo "<a href=\"invoiceChooseDate.php?exist=0&tab=admin:admin_sales:invoice:createinvoice\"> Create a new invoice </a> ";
	 }
   } else {
      // creating new invoice
      $sql="select nextNum, prefix from targets where targetName='".$target."'";
      $res = mysql_query($sql);
      echo mysql_error();
      if (mysql_num_rows($res) != 1) {
         die("Sales Targets Table Corrupted");
      }
      $row = mysql_fetch_array($res);
      $num = $row['nextNum'];
      $prefix = $row['prefix'];
      $sql = "update targets set nextNum = nextNum + 1 where targetName='".
         $target."'";
      mysql_query($sql);
      echo mysql_error();
      $sql="INSERT INTO invoice_master(invoice_id, salesDate, target, comments)".
        " VALUES('".$prefix.$num."', '".$listDate."', '".$target."', '')";
      mysql_query($sql);
      $currentIDTable=mysql_query("SELECT LAST_INSERT_ID()");
      $currentIDRow=mysql_fetch_array($currentIDTable);
      $currentID= $currentIDRow['LAST_INSERT_ID()'];
//        echo "<script>alert(\"Creating New Invoice\");</script> \n";
      echo ' <meta http-equiv="refresh" content=0;URL="invoiceEntry.php?year='.
        $year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.
        '&target='.encodeURIComponent($target).'&invoiceID='.$prefix.$num.
        '&tab=admin:admin_sales:invoice:createinvoice ">';
   }
} else if(isset($_POST['submit']) && !isset($_POST['target'])){
  echo "<script>alert('Please select a sales target!');</script>";
}
?>
</form>

</body>
</html>
