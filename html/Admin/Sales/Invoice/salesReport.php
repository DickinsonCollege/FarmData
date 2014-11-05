<?php session_start(); 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<form name='form' method='POST' action="<?php $_PHP_SELF ?>">
<h3> Invoice/Sales Report </h3>
<br clear="all"/>
<label for="form">From:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="to">To:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<label for="target">Sales Target:&nbsp;</label>
<div id='targDiv' class='styled-select'>
<select name="target" id="target">
<option value="%" selected>All</option>
<?php
$sql = "select targetName from targets";
$result=mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)){
  echo '<option value= "'.$row1['targetName'].'">'.$row1['targetName'].'</option>';
}
?>
</select>

<br clear="all"/>
<br clear="all"/>

<input class = "submitbutton" type="submit" name="submit" value="Submit">
</form>
<?php
if(isset($_POST['submit'])) {
      $year = $_POST['year'];
      $month = $_POST['month'];
      $day = $_POST['day'];
      $tcurYear = $_POST['tyear'];
      $tcurMonth = $_POST['tmonth'];
      $tcurDay = $_POST['tday'];
      $target = $_POST['target'];

      $sql="Select date(salesDate),invoice_master.invoice_no, ".
         "invoice_master.invoice_id,target, sum(price_case*cases) as total,".
         " comments from invoice_entry,invoice_master where date(salesDate)".
         " between '".  $year."-".$month."-".$day."' AND '".$tcurYear."-".
         $tcurMonth."-".$tcurDay.
         "' and invoice_master.invoice_no=invoice_entry.invoice_no ".
         "and target like '".$target.
         "' group by date(salesDate), invoice_master.invoice_no, comments".
         " order by salesDate,invoice_no";
      $result=mysql_query($sql);

      echo '<br clear="all"/>';
      echo '<br clear="all"/>';
      echo "<table border>";
      echo "<caption>Sales Report Between ".$year."-".$month
      ."-".$day." and ".$tcurYear."-".$tcurMonth."-".$tcurDay."  </caption>";
      echo "<tr><th>Sales Date</th><th><center>Invoice #</center></th>".
        "<th>Target</th><th>View Invoice</th><th>Send Email</th>".
        "<th><center>Total</center></th><th><center>Notes</center></th></tr>";
      while ($row= mysql_fetch_array($result)) {
        echo "<tr><td>";
        echo $row['date(salesDate)'];
        echo "</td><td>";
        echo $row['invoice_id'];
        echo "</td><td>";
        echo $row['target'];
        echo "</td><td>";
        //echo "<a href=\"/Admin/Sales/Invoice/viewInvoice.php?invoice=".$row['invoice_no'].
        //   "&total=".$row['total']."&tab=admin:admin_sales:invoice:invoicereport\">View</a>";
        // echo "<form method='POST' action=\"/Admin/Sales/Invoice/viewInvoice.php?invoice=".$row['invoice_no'].
        echo "<form method='POST' action=\"viewInvoice.php?invoice=".
           $row['invoice_no']."&target=".encodeURIComponent($row['target']).
           "&invoiceID=".$row['invoice_id'].
           "&total=".$row['total']."&tab=admin:admin_sales:invoice:invoicereport\">";
        echo "<input type='submit' class='submitbutton' value='View'></form>";
        echo "</td><td>";
        //echo "<a href=\"/Admin/Sales/Invoice/view2.php?invoice=".$row['invoice_no']."&total=".
         //  $row['total']."&salesDate=".$row['date(salesDate)'].
          // "&tab=admin:admin_sales:invoice:invoicereport\">Send</a>";
        // echo "<form method='POST' action=\"/Admin/Sales/Invoice/view2.php?invoice=".$row['invoice_no']."&total=".
        echo "<form method='POST' action=\"view2.php?invoice=".$row['invoice_no']."&total=".
           $row['total']."&salesDate=".$row['date(salesDate)'].
           "&target=".encodeURIComponent($row['target']).
           "&invoiceID=".$row['invoice_id'].
           "&tab=admin:admin_sales:invoice:invoicereport\">";
        echo "<input type='submit' class='submitbutton' value='Send'></form>";
        echo "</td><td>";
        echo "$".number_format((float) $row['total'], 2, '.', '');
        echo "</td><td>";
        echo $row['comments'];
        echo "</td><tr>";
      }

      echo "</table>";
      echo '<br clear="all"/>';
      echo "<form name='downloadform' method='POST' action='/down.php'>";
      $sql="Select salesDate,invoice_master.invoice_id,target,product, cases, price_case as price_per_case, price_case*cases as total, approved_by, comments from invoice_entry,invoice_master where date(salesDate) between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and invoice_master.invoice_no=invoice_entry.invoice_no  order by salesDate,invoice_master.invoice_no";
      
      echo "<input type = \"hidden\" name = \"query\" value = \"".$sql."\">";
      echo '<input class="submitbutton" type="submit" name="submit" value="Download Full Report">';
      echo "</form>";
}
?>
