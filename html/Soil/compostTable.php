<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>

<?php
   // Get values from compostTable.php
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $pileID = $_GET['pileID'];
   $fieldID = $_GET['fieldID'];
   if (isset($_GET['id']) && isset($_GET['ctype'])) {
       $ctype = $_GET['ctype'];
       if ($ctype == 'accum') {
          $sqlDel="DELETE FROM compost_accumulation WHERE id=".$_GET['id'];
          try {
             $stmt=$dbcon->prepare($sqlDel);
             $stmt->execute();
          } catch (PDOException $p) {
             phpAlert('', $p);
             die();
          }
       } else if ($ctype == 'act') {
          $sqlDel="DELETE FROM compost_activity WHERE id=".$_GET['id'];
          try {
             $stmt=$dbcon->prepare($sqlDel);
             $stmt->execute();
          } catch (PDOException $p) {
             phpAlert('', $p);
             die();
          }
       } else if ($ctype == 'temp') {
          $sqlDel="DELETE FROM compost_temperature WHERE id=".$_GET['id'];
          try {
             $stmt=$dbcon->prepare($sqlDel);
             $stmt->execute();
          } catch (PDOException $p) {
             phpAlert('', $p);
             die();
          }
       } else if ($ctype == 'app') {
          $sqlDel="DELETE FROM utilized_on WHERE id=".$_GET['id'];
          try {
             $stmt=$dbcon->prepare($sqlDel);
             $stmt->execute();
          } catch (PDOException $p) {
             phpAlert('', $p);
             die();
          }
       } else {
          die("unknown compost record type");
       }
   }

   // Create Header
   echo "<center>";
   if($fieldID == "%" && $pileID == "%") {
      echo "<h2 class='hi'> Compost Records for All Fields and All Compost Piles</h2>";
   } else if ($fieldID == "%") {
      echo "<h2 class='hi'> Compost Records for All Fields and Compost Pile: ".
    $_GET['pileID']."</h2>";
   } else if ($pileID == "%") {
      echo "<h2 class='hi'> Compost Records for Field: ".$_GET['fieldID'].
      " and All Compost Piles</h2>";
   } else {
      echo "<h2 class='hi'> Compost Records for Field: ".$_GET['fieldID']. 
        " and Compost Pile: ".$_GET['pileID']."</h2>";
   }
   echo "</center>";
   // echo "<div style='margin-bottom:50px'></div>";
?>

<?php
   // Accumulation Records
   $accumulationSQL = "SELECT id, accDate, pileID, material, pounds, cubicyards, comments 
      FROM compost_accumulation 
      WHERE accDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND pileID LIKE '".$pileID."' ORDER BY accDate";
   try {
      $result = $dbcon->query($accumulationSQL);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   echo "<h4>Compost Accumulation Records</h4>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Accumulation Date</th><th>Pile ID</th><th>Material</th><th>Pounds</th><th>Cubic Yards</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['accDate'];
      echo "</td><td>";
      echo $row['pileID'];
      echo "</td><td>";
      echo $row['material'];
      echo "</td><td>";
      echo $row['pounds'];
      echo "</td><td>";
      echo $row['cubicyards'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td><form method='POST' action=\"compostAccumEdit.php?month=".$month."&day=".$day."&year=".
            $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&pileID=".encodeURIComponent($_GET['pileID']).
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&tab=soil:soil_fert:soil_compost:compost_report\">";
         echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";

         echo "<td><form method='POST' action=\"compostTable.php?month=".$month."&day=".$day."&year=".
            $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&pileID=".encodeURIComponent($_GET['pileID'])."&ctype=accum".
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&tab=soil:soil_fert:soil_compost:compost_report\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'></form></td>";
      }
      echo "</tr>";
   }
   echo "</table>";
   echo "<form name='accumulationForm' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($accumulationSQL).'" name="query" id="query">';
   echo '<input type="submit" name="submit" class="submitbutton pure-button" value="Download Report">';
?>
</form>

<?php
   // Activity Records
   $activitySQL = "SELECT id, actDate, pileID, activity, comments FROM compost_activity ".
      "WHERE actDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' AND pileID LIKE '".$pileID."' ORDER BY actDate";
   try {
      $result = $dbcon->query($activitySQL);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   echo "<h4>Compost Activity Records</h4>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Activity Date</th><th>Pile ID</th><th>Activity</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['actDate'];
      echo "</td><td>";
      echo $row['pileID'];
      echo "</td><td>";
      echo $row['activity'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td><form method='POST' action=\"compostActEdit.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID']).
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=soil:soil_fert:soil_compost:compost_report\">";
      echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";

      echo "<td><form method='POST' action=\"compostTable.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID'])."&ctype=act".
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=soil:soil_fert:soil_compost:compost_report\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'></form></td>";

      }
      echo "</tr>";
   }
   echo "</table>";
   echo "<form name='activityForm' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($activitySQL).'" name="query" id="query">';
   echo "<input type='submit' name='submit' class='submitbutton pure-button' value='Download Report'>";
?>
</form>

<?php
   // Temperature Records
   $temperatureSQL = "SELECT tmpDate, pileID, temperature, numReadings, comments, id ".
      "FROM compost_temperature ".
      "WHERE tmpDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' AND pileID LIKE '".$pileID."' ORDER BY tmpDate";
   try {
      $result = $dbcon->query($temperatureSQL);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   echo "<h4>Compost Temperature Reading Records</h4>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Reading Date</th><th>Pile ID</th><th>Temperature</th><th>Number of Readings</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['tmpDate'];
      echo "</td><td>";
      echo $row['pileID'];
      echo "</td><td>";
      echo $row['temperature'];
      echo "</td><td>";
      echo $row['numReadings'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td><form method='POST' action=\"compostTempEdit.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID']).
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=soil:soil_fert:soil_compost:compost_report\">";
      echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";

      echo "<td><form method='POST' action=\"compostTable.php?month=".$month."&day=".$day."&year=".
         $year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&pileID=".encodeURIComponent($_GET['pileID']).
         "&fieldID=".encodeURIComponent($_GET['fieldID'])."&ctype=temp".
         "&tab=soil:soil_fert:soil_compost:compost_report\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'></form></td>";
      }
      echo "</tr>";
   }
   echo "</table>";
   echo "<form name='temperatureForm' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($temperatureSQL).'" name="query" id="query">';
   echo "<input type='submit' name='submit' class='submitbutton pure-button' value='Download Report'>";
?>
</form>

<?php
   // Application Records
   $applicationSQL = "SELECT util_date, fieldID, incorpTool, pileID, tperacre, incorpTiming, fieldSpread,".
      " comments, id FROM utilized_on ".
      "WHERE util_date between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
      "' AND fieldID LIKE '".$fieldID."' AND pileID LIKE '".$pileID."' ORDER BY util_date";
   try {
      $result = $dbcon->query($applicationSQL);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }

   echo "<h4>Compost Application Records</h4>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Utilized Date</th><th>Field ID</th><th>Pile ID</th><th>Incorporation Tool</th><th>Incorporation Timing</th><th>Tons per Acre</th><th>Area Spread (acres)</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td>";
        echo $row['util_date'];
        echo "</td><td>";
        echo $row['fieldID'];
        echo "</td><td>";
        echo $row['pileID'];
        echo "</td><td>";
        echo $row['incorpTool'];
        echo "</td><td>";
        echo $row['incorpTiming'];
        echo "</td><td>";
        echo number_format((float) $row['tperacre'], 2, '.', '');
        echo "</td><td>";
        echo $row['fieldSpread'];
        echo "</td><td>";
        echo $row['comments'];
        echo "</td>";
        if ($_SESSION['admin']) {
           echo "<td><form method='POST' action=\"compostEdit.php?month=".$month."&day=".$day.
              "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
              "&id=".$row['id'].
              "&fieldID=".encodeURIComponent($_GET['fieldID']).
              "&pileID=".encodeURIComponent($_GET['pileID']).
              "&tab=soil:soil_fert:soil_compost:compost_report\">";
           echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";
           echo "<td><form method='POST' action=\"compostTable.php?month=".$month."&day=".$day.
              "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay.
              "&id=".$row['id'].
              "&pileID=".encodeURIComponent($_GET['pileID']).
              "&fieldID=".encodeURIComponent($_GET['fieldID'])."&ctype=app".
              "&tab=soil:soil_fert:soil_compost:compost_report\">";
           echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
           echo "onclick='return warn_delete();'></form></td>";
        }
        echo "</tr>";
   }
   echo "</table>";
   echo "<form name='applicationForm' method='POST' action='/down.php'>";
   echo '<input type="hidden" value="'.escapehtml($applicationSQL).'" name="query" id="query">';
   echo '<input type="submit" name="submit" class="submitbutton pure-button" value="Download Report">';
   echo "</form>";

   echo '<br clear="all"/>';

   echo '<div class="pure-form pure-form-aligned">';
   if ($fieldID != "%") {
      $sqlget = "Select sum(tperacre) as total, avg(tperacre) as average from utilized_on ".
         "where util_date between '".  $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and  fieldID like '".$fieldID."' and pileID like '".  $pileID."'";
      $result = $dbcon->query($sqlget);
      while ($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
           $row3Deci3=number_format((float)$row1['total'], 2, '.', '');
      echo '<div class="pure-control-group">';
      echo "<label for='total'>Total Tons Per Acre:</label> ";
           echo "<input readonly class='textbox2 mobile-input' type ='text' value=".$row3Deci3.">";
      echo '</div>';
      $row3Deci3=number_format((float)$row1['average'], 2, '.', '');
      echo '<div class="pure-control-group">';
      echo "<label for='total'>Average Tons Per Acre:</label> ";
      echo "<input readonly class='textbox2 mobile-input' type ='text' value=".$row3Deci3.">";
      echo '</div>';
     }
   }

   if ($pileID != '%') {
     $sql = "select sum(tperacre * fieldSpread) as tons from utilized_on".
         " where util_date between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and  fieldID like '".$fieldID."' and pileID like '".$pileID."'";
     $result = $dbcon->query($sql);
     while ($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="pure-control-group">';
        echo "<label for='tons'>Total Tons Applied from Pile ".
           $_POST['pileID'].":</label> ";
        $tons=number_format((float)$row1['tons'], 2, '.', '');
        echo "<input readonly class='textbox2 mobile-input' type ='text' value=".$tons.">";
 echo '</div>';
      }
    }
    echo '</div>';
?>



<?php
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<form method="POST" action="compostReport.php?tab=soil:soil_fert:soil_compost:compost_report">';
echo '<input type="submit" class="submitbutton pure-button wide" value = "Run Another Report">';
echo '</form>';
?>
