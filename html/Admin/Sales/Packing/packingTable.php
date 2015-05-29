<?php session_start();

include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>

<?php
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM pack WHERE id=".$_GET['id'];
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
   $bringback = $_GET['bringback'];

   $sql = "SELECT id, packDate, crop_product, grade, amount, unit, comments, bringBack, target FROM pack 
      WHERE packDate BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND pack.crop_product like '".$crop_product."' AND pack.target like '".$target."' AND pack.grade like '".$grade."'";
   if ($bringback != "%") {
      $sql .= " AND bringback=".$bringback;
   }
   $sql .= " ORDER BY packDate, crop_product, target, grade";
   
   $result = mysql_query($sql);
   
   echo "<table class = 'pure-table pure-table-bordered'>";
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

   echo "<center><h2>Packing Report for ".$crpProd."<br>
         To: ".$trg." of Grade: ".$grd."<br>
         ".$dat."</h2></center>";

   echo "<thead><tr><th>Date</th>
      <th>Crop/Product</th>
      <th>Target</th>
      <th>Grade</th>
      <th>Amount</th>
      <th>Unit</th>
      <th style='width:20%'>Comments</th>
      <th>Bring Back</th><th>Edit</th><th>Delete</th></tr></thead>";
   while ($row = mysql_fetch_array($result)) {
      echo "<tr>";
      echo "<td>";
      echo $row['packDate'];
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
      $convresult = mysql_query($convsql);
      if (mysql_num_rows($convresult) > 0) {
         $convrow = mysql_fetch_array($convresult);
         $conversion = $convrow[0];
                        $amount = $amount * $conversion;
                        $unit = 'POUND';
      }
      echo number_format((float) $amount, 2, '.', '');
      echo "</td><td>";
      echo $unit;
      echo "</td><td>";
      echo $row['comments'];
      echo "</td><td>";
      if ($row['bringBack'] == 1) {
         echo "Yes";
      } else {
         echo "No";
      }
      echo "</td>";
      echo "<td><form method=\"POST\" action=\"packEdit.php?month=".$month.
         "&day=".$day."&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear.
         "&tday=".$tcurDay."&id=".$row['id'].
          "&crop_product=".encodeURIComponent($crop_product).
          "&target=".encodeURIComponent($target)."&grade=".$grade."&bringback=".$bringback.
          "&tab=admin:admin_sales:packing:packing_report\">";
      echo "<input type=\"submit\" class=\"editbutton pure-button wide\" value=\"Edit\"></form> </td>";

      echo "<td><form method=\"POST\" action=\"packingTable.php?month=".
         $month."&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
         "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&crop_product=".encodeURIComponent($crop_product).
         "&target=".encodeURIComponent($target)."&grade=".$grade."&bringback=".$bringback.
          "&tab=admin:admin_sales:packing:packing_report\">";
      echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"";
      echo "onclick='return warn_delete();'></form></td>";

      echo "</tr>";
   }
   echo "</table>";
   echo "<br clear='all'>";
echo "<div class = 'pure-g'>";
echo "<div class = 'pure-u-1-2'>";
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type=\"hidden\" name=\"query\" value=\"".escapehtml($sql)."\">";
echo "<input class='submitbutton pure-button wide' type='submit' name='submit' value='Download Report'>";
echo "</form>";
echo "</div>";

echo "<div class = 'pure-u-1-2'>";
echo "<form method='POST' action='packingReport.php?tab=admin:admin_sales:packing:packing_report'>";
echo "<input type='submit' class='submitbutton pure-button wide' value='Run Another Report'></form>";
echo "</div>";
?>
