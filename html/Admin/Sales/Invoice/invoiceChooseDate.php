<?php
session_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<form name='date' class = 'pure-form pure-form-aligned' method='POST' action="<?php $_PHP_SELF ?>">
<script type="text/javascript">
function show_confirm() {
  return confirm("Confirm Deletion");
}
</script>
<?php
if($_GET['exist']==1 || $_POST['hidden']==1){
   echo "<center><h2>Edit Invoice </h2></center>";
   $exist = 1;
}else{
   echo "<center><h2>Create Invoice </h2></center>";
   $exist = 0;
}
?>

<div class = 'pure-control-group'>
<label for="target">Sales Target:</label>
<select name="target" id="target">
<?php
if ($exist) {
   echo '<option value="%">All</option>';
} else {
//   echo '<option value=0 selected disabled>Sales Target</option>';
}
$sql = "select targetName from targets where active = 1";
$result = $dbcon->query($sql);
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
  $targ = $row1['targetName'];
  if ($targ != 'Loss') {
     echo '<option value= "'.escapeHTML($targ).'">'.$targ.'</option>';
  }
}
?>
</select></div>

<div class = 'pure-control-group'>
<?php
if ($exist) {
  echo '<label for="date">From:</label>';
} else {
  echo '<label for="date">Invoice Date:</label>';
}
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
if ($exist) {
   echo '<br clear="all"/>';
   echo '<label for="date2">To:</label>';
   include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
}
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '</div>';

if($exist){
   echo "<input type='hidden' name='hidden' value=".$_GET['exist'] .">";
}else{
   echo "<input type='hidden' name='hidden' value=".$_POST['hidden'] .">";
}
?>
<input class="submitbutton pure-button wide" type="submit" name="submit" value="Choose" >
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

      $sql="SELECT invoice_id,invoice_no, salesDate,target FROM invoice_master WHERE".
         " salesDate between '".$listDate."' and '".$tcurYear."-".$tcurMonth."-".$tcurDay.
         "' and target like '".$target."'";
      $sql_result = $dbcon->query($sql);
      $row = $sql_result->fetch(PDO::FETCH_ASSOC);
      if ($row) {
        echo "<table border class = 'pure-table pure-table-bordered'>";
        echo "<thead><tr><th>Sales Date</th><th><center>Invoice No.</center></th>".
             "<th>Customer</th><th>Edit</th><th>Delete</th></tr></thead>";
        do {
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
           echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form>";
           echo "</td><td>";
           echo '<form method="POST" action="invoiceDelete.php?invoice='.$row['invoice_no'].
              '&tab=admin:admin_sales:invoice:editinvoice">';
           echo "<input type='submit' class='deletebutton pure-button wide' ".
                "onclick='return show_confirm();' value='Delete'></form>";
          echo "</td></tr>";
        } while ($row = $sql_result->fetch(PDO::FETCH_ASSOC));
        echo "</table>";

     }else{
        echo "<script>alert(\"Did not find an existing invoice, please create a new invoice\");</script> \n";
        echo "<a href=\"invoiceChooseDate.php?exist=0&tab=admin:admin_sales:invoice:createinvoice\"> ".
             "Create a new invoice </a> ";
     }
   } else {
      // creating new invoice
      $sql="select nextNum, prefix from targets where targetName='".$target."'";
      $res = $dbcon->query($sql);
      $row = $res->fetch(PDO::FETCH_ASSOC);
      if (!$row) {
         die("No such sales target: ".$target);
      }
      $num = $row['nextNum'];
      $prefix = $row['prefix'];
      $sql = "update targets set nextNum = nextNum + 1 where targetName='".$target."'";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not update invoice number", $p);
         die();
      }
      $sql="INSERT INTO invoice_master(invoice_id, salesDate, target, comments)".
        " VALUES('".$prefix.$num."', '".$listDate."', '".$target."', '')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert("Could not create invoice", $p);
         die();
      }
      $currentID = $dbcon->lastInsertId();
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
