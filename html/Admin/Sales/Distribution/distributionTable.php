<?php session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>

<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM distribution WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $crop_product = $_GET['crop_product'];
   $target = $_GET['target'];
   $grade = $_GET['grade'];

   $sql = "SELECT id, pricePerUnit, distDate, crop_product, grade, amount, unit, comments, target FROM distribution 
      WHERE distDate BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND crop_product like '".$crop_product."' AND target like '".$target."' AND grade like '".$grade."' 
      ORDER by distDate, crop_product, target, grade";
   $result = mysql_query($sql);
   
   echo "<table>";
   $crpProd = $_GET['crop_product'];
   if ($crpProd === "%") {
      $crpProd = "All Crops/Products";
   }
   $trg = $_GET['target'];
   if ($trg === "%") {
      $trg = "All Targets";
   }
   $grd = $_GET['grade'];
   if ($grd === "%") {
      $grd = "All";
   }
   if ($year == $tcurYear && $month == $tcurMonth && $day == $tcurDay) {
      $monthName = date('F', mktime(0, 0, 0, $month, 10));
      $dat = "On Date: ".$monthName." ".$day." ".$year;
   } else {
      $monthName = date('F', mktime(0, 0, 0, $month, 10));
      $tcurMonthName = date('F', mktime(0, 0, 0, $tcurMonth, 10));
      $dat = "From: ".$monthName." ".$day." ".$year."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; To: ".$tcurMonthName." ".$tcurDay." ".$tcurYear;
   }

   echo "<caption>Distribution Report for ".$crpProd."<br>
         To: ".$trg." &nbsp;&nbsp;&nbsp Grade: ".$grd."<br>
         ".$dat."</caption>";

   echo "<tr><th>Date</th>
      <th>Crop/Product</th>
      <th>Target</th>
      <th>Grade</th>
      <th>Amount</th>
      <th>Unit</th>
      <th>Price/Unit</th>
      <th>Total Price</th>
      <th style='width:20%'>Comments</th><th>Edit</th><th>Delete</th></tr>";
   $total = 0;
   while ($row = mysql_fetch_array($result)) {
      echo "<tr>";
      echo "<td>";
      echo $row['distDate'];
      echo "</td><td>";
      echo $row['crop_product'];
      echo "</td><td>";
      echo $row['target'];
      echo "</td><td>";
      echo $row['grade'];
      echo "</td><td>";
      $amount = $row['amount'];
      $unit = $row['unit'];
      $convsql = "SELECT conversion FROM units WHERE crop='".$row['crop_product'].
      "' AND unit='POUND'";
      $converted = false;
      $convresult = mysql_query($convsql);
      if (mysql_num_rows($convresult) > 0) {
         $converted = true;
         $convrow = mysql_fetch_array($convresult);
         $conversion = $convrow[0];
         $amount = $amount * $conversion;
         $unit = 'POUND';
      }
      echo number_format((float) $amount, 2, '.', '');
      echo "</td><td>";
      echo $unit;
      echo "</td><td>";
      if (!$converted) {
         $conversion = 1;
      }
      $price = $row['pricePerUnit'] / $conversion;
      echo number_format((float) $price, 2, '.', '');
      echo "</td><td>";
      echo number_format((float) ($price * $amount), 2, '.', '');
      $total += ($price * $amount);
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      echo "<td><form method=\"POST\" action=\"distributionEdit.php?month=".
         $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
         "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&crop_product=".encodeURIComponent($crop_product).
         "&target=".encodeURIComponent($target)."&grade=".$grade.
         "&tab=admin:admin_sales:distribution:distribution_report\">";
      echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form> </td>";

      echo "<td><form method=\"POST\" action=\"distributionTable.php?month=".
         $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
         "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&crop_product=".encodeURIComponent($crop_product).
         "&target=".encodeURIComponent($target)."&grade=".$grade.
         "&tab=admin:admin_sales:distribution:distribution_report\">";
      echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"";
      echo "onclick='return warn_delete();'></form></td>";

      echo "</tr>";
   }
   echo "</table>";
   echo "<br clear='all'>";
   if ($total > 0) {
      echo '<label for="total"> Total Revenue ($): &nbsp;</label> '.
         '<input disabled type="textbox" name="total" '.
         'id="total" class="textbox25" value='
         .number_format((float) $total, 2, '.', '').'>';
      echo "<br clear='all'>";
      echo "<br clear='all'>";
   }
 
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type=\"hidden\" name=\"query\" value=\"".escapehtml($sql)."\">";
echo "<input class='submitbutton' type='submit' name='submit' value='Download Report'>";
echo "</form>";
echo "<form method='POST' action='distributionReport.php?tab=admin:admin_sales:distribution:distribution_report'>";
echo "<input type='submit' class='submitbutton' value='Run Another Report'></form>";
?>
