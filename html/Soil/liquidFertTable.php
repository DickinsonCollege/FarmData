<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM liquid_fertilizer WHERE id=".$_GET['id'];
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
   }

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $material = escapehtml($_GET['material']);
   $sql = "select id, username, inputDate, fieldID, fertilizer, dripRows, unit, username, quantity, comments ".
      "from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and fertilizer like '".$material."' order by inputDate";
   $sqldata = $dbcon->query($sql);
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_GET['fieldID'];
   } 
   if( $material == "%") {
      $mat = "All Materials";
   } else {
      $mat = $_GET['material'];
   } 
   echo "<center>";
   echo "<h2> Liquid Fertilizer Application Report for ".$mat." in Field: ".$fld."  </h2>";
   echo "</center>";
   echo "<table class='pure-table pure-table-bordered'>";
   
   echo "<thead><tr><th>Date</th><th>Field</th><th>Material</th><th>number of drip rows</th><th>unit<br>".
     "</th><th>Total Material Applied</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>User</th><th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['inputDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['fertilizer'];       
      echo "</td><td>";
      echo $row['dripRows'];
      echo "</td><td>";
      echo $row['unit'];
      echo "</td><td>";
      echo number_format((float)$row['quantity'], 2, '.', '');
      // echo $row['quantity'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td>".$row['username']."</td>";
         echo "<td><form method=\"POST\" action=\"liquidFertilizerEdit.php?month=".$month."&day=".$day.
           "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
           "&fieldID=".$_GET['fieldID']."&material=".$_GET['material'].
           "&tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report\">";
         echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton pure-button wide\" value=\"Edit\"></form></td>";
   
         echo "<td><form method=\"POST\" action=\"liquidFertTable.php?month=".$month."&day=".$day.
            "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".$_GET['fieldID']."&material=".$_GET['material'].
           "&tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report\">";
         echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"";
         echo "onclick='return warn_delete();'></form>";
         echo "</td>";
      }
      echo "</tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($material != "%") {
      $total = "select ".
         "sum(case when unit='QUARTS' then quantity / 4 else quantity end) as total ".
         "from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
         "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and fertilizer like '".$material."'";

      $result = $dbcon->query($total);
      echo "<div class='pure-form pure-form-aligned'>";
      while ($row1 = $result->fetch(PDO::FETCH_ASSOC)  ) {
         echo "<div class='pure-control-group'>";
        echo "<label for='total'>Total ".$material." Applied:</label> ";
        echo "<input readonly class='textbox2'  type ='text' value=".
          number_format((float)$row1['total'], 2, '.', '').">";
        echo "&nbsp; GALLONS";
        echo '</div>';
     }
     echo '</div>';
  }
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';

   echo '<div class="pure-g">';
   echo '<div class="pure-u-1-2">';
   echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
   echo "</form>";
   echo '</div>';
   echo '<div class="pure-u-1-2">';
   echo '<form method="POST" action = "liquidFertReport.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
   echo '</div>';
   echo '</div>';

?>
</div>
