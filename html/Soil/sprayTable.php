<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM bspray WHERE id=".$_GET['id'];
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
   $sprayMaterial = escapehtml($_GET['sprayMaterial']);
   $fieldID = escapehtml($_GET['fieldID']);
   $crop = escapehtml($_GET['crop']);
   $sql = "Select id, sprayDate, fieldID, water, materialSprayed, rate, BRateUnits, totalMaterial, ".
        "mixedWith, crops, comments from bspray, tSprayMaterials where sprayDate between '".
        $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
        $fieldID."'and materialSprayed like '".$sprayMaterial."' and crops like '%".$crop.
        "%' and materialSprayed = sprayMaterial order by sprayDate";
   $sqldata = $dbcon->query($sql);
   if ($fieldID == "%") {
     $fld = "All Fields";
   } else {
     $fld = "Field ".$fieldID;
   }
   if ($sprayMaterial == "%") {
      $mat = "All Materials";
   } else {
      $mat = $sprayMaterial;
   }
   echo "<center>";
   echo "<h2>Backpack Spray Report for ".$mat." on ".$fld."</h2>";
   echo "</center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Spray Date</th><th>Field ID</th><th>Water (Gallons)</th><th>Material Sprayed</th><th>Rate</th><th>Total Material</th><th>Mixed With</th><th>Crops</th><th> Comments </th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['sprayDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['water'];       
      echo "</td><td>";
      echo $row['materialSprayed'];
      echo "</td><td>";
      echo $row['rate']." ".$row['BRateUnits']."/gallon";
      echo "</td><td>";
      echo $row['totalMaterial'];
      echo "</td><td>";
      echo $row['mixedWith'];
      echo "</td><td>";
      echo $row['crops'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td><form method='POST' action=\"bsprayEdit.php?month=".$month.
            "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
            "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&sprayMaterial=".encodeURIComponent($_GET['sprayMaterial']).
            "&tab=soil:soil_spray:bspray:bspray_report\">";
         echo "<input type='submit' class='editbutton pure-button wide' value='Edit' /></form>";
         echo "</td><td>";
         echo "<form method='POST' action=\"sprayTable.php?month=".$month.
            "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
            "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&sprayMaterial=".encodeURIComponent($_GET['sprayMaterial']).
            "&tab=soil:soil_spray:bspray:bspray_report\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'/></form>";
         echo "</td>";
     }
     echo "</tr>";
     echo "\n";
}
      echo "</table>";
      echo '<br clear="all"/>';
      echo '<div class="pure-form pure-form-aligned">';
      $total="Select sum(water) as water, sum(totalMaterial) as total from bspray where sprayDate between '".
         $year."-".$month."-".$day.
         "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and materialSprayed like '".$sprayMaterial.
         "' and fieldID like '".$fieldID."' and crops like '%".$crop."%'";
      $result=$dbcon->query($total);
      $other = "Select BRateUnits from tSprayMaterials where sprayMaterial like '".$sprayMaterial."'";
      $result2 = $dbcon->query($other);
      $row2 = $result2->fetch(PDO::FETCH_ASSOC);
      while ($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="pure-control-group">';
        echo "<label for='total'>Total Gallons of Water Used:</label> ";
   echo "<input readonly class='textbox2 mobile-input' type ='text' value=".$row1['water'].">";
        echo "</div>";
        if ($sprayMaterial != "%") {
           echo '<div class="pure-control-group">';
           echo "<label for='total'>Total Material Used:</label> ";
      echo "<input readonly class='textbox2 mobile-input'  type ='text' value=".$row1['total'].">";
      echo "&nbsp;".$row2['BRateUnits']."(S)";
        echo "</div>";
        }
      }
      echo "</div>";
      echo '<br clear="all"/>';
      echo '<br clear="all"/>';
      echo '<div class="pure-g">';
      echo '<div class="pure-u-1-2">';
      echo "<form name='form' method='POST' action='/down.php'>";
      echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
      echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
      echo '</form>';
      echo '</div>';
      echo '<div class="pure-u-1-2">';
      echo '<form method="POST" action = "sprayReport.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
      echo '</div>';
      echo '</div>';
?>
</div>
</body>
</html>
