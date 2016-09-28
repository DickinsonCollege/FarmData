<?php session_start();?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM harvested WHERE id=".$_GET['id'];
      try {
        $res=$dbcon->prepare($sqlDel);
        $res->execute();
      } catch (PDOException $p) {
         phpAlert('Error deleting record', $p);
      }
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $genSel = $_GET['genSel'];
   $crop = escapehtml($_GET["crop"]);
   $fieldID = escapehtml($_GET["fieldID"]);
   $sql = "SELECT id, username, gen, hardate,fieldID, harvested.crop,yield,unit,hours,comments FROM harvested where hardate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and harvested.crop like '" .$crop."' and fieldID like '".
      $fieldID."' and gen like '".$genSel."' order by hardate";
   try {
      $sqldata = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
   echo "<table class='pure-table pure-table-bordered'>";
   $crp = $crop;
   if ($fieldID == "%") {
       $flb = "All Fields";
   } else {
       $flb = $fieldID;
   }
   if ($crp == "%") {
       $clb = "All Crops";
   } else {
       $clb = $crp;
   }
   if ($genSel == "%") {
       $glb = "All Successions";
   } else {
       $glb = "Succession ".$genSel;
   }
  echo "<center>";
  echo "<h2> Harvest Report for ".$clb." in ".$flb;
  if ($_SESSION['gens']) {
     echo " of ".$glb;
  }
  echo "</h2>";
  echo "</center>";
   echo "<thead><tr><th>Date</th><th>Field</th><th>Crop</th><th>Yield</th><th>Unit</th>";
   if ($_SESSION['gens']) {
      echo "<th>Succ&nbsp;#</th>";
   }
   if ($_SESSION['labor']) {
      echo "<th>Hours</th>";
   }
   echo "<th> &nbsp;  Comments  </th>";
   if ($_SESSION['admin']) {
      echo "<th>User</th>";
      echo "<th>Edit</th>";
      echo "<th>Delete</th>";
   }
   echo "</tr></thead>";
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr><td>";
      echo $row['hardate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['crop'];       
      echo "</td><td>";
      echo number_format((float) $row['yield'], 2, '.', '');
      echo "</td><td>";
      echo $row['unit'];
      echo "</td><td>";
      if ($_SESSION['gens']) {
         echo $row['gen'];
         echo "</td><td>";
      }
      if ($_SESSION['labor']) {
         echo number_format((float) $row['hours'], 2, '.', '');
         echo "</td><td>";
      }
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td>".$row['username']."</td>";
         echo "<td><form method=\"POST\" action=\"harvestEdit.php?month=".$month."&day=".$day."&year=".$year.
            "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&crop=".encodeURIComponent($_GET['crop']).
            "&fieldID=".encodeURIComponent($_GET['fieldID'])."&genSel=".$genSel.
            "&tab=harvest:harvestReport&submit=Submit\">";
         echo "<input type=\"submit\" class=\"editbutton pure-button wide\" value=\"Edit\"></form> </td>";

         echo "<td><form method=\"POST\" action=\"harvestTable.php?month=".$month."&day=".$day."&year=".$year.
            "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&crop=".encodeURIComponent($_GET['crop']).
            "&fieldID=".encodeURIComponent($_GET['fieldID'])."&genSel=".$genSel.
            "&tab=harvest:harvestReport&submit=Submit\">";
         echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"";
         echo "onclick='return warn_delete();'></form></td>";
      }
      echo "</tr>";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($crop != "%") {
      $total="Select sum(yield) as total, sum(harvested.hours) as hours, unit from harvested ".
         "where hardate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
         "' and harvested.crop like '" .$crop.  "' and harvested.fieldID like '".
         $fieldID."' and gen like '".$genSel."' group by unit order by unit";
      try {
         $res=$dbcon->query($total);
      } catch (PDOException $p) {
         phpAlert('', $p);
      }
      $yield="Select unit, sum(yield)/(Select sum(tft) from 
         ((Select bedft as tft from dir_planted where fieldID like '".$fieldID.
         "' and year(plantdate) between '".$year."' and '".$tcurYear."' and 
         dir_planted.crop= '".$crop."' and dir_planted.gen like '".$genSel."') union all 
         (Select bedft as tft from transferred_to where year(transdate) between '".$year."' and '".
            $tcurYear."' and transferred_to.crop= '".$crop.
            "' and fieldID like '".$fieldID."' and gen like '".$genSel."')) as temp1) as yperft from harvested where hardate between '".
            $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
            "' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
            $fieldID."' and gen like '".$genSel."' group by unit order by unit";
      try {
         $res2=$dbcon->query($yield);
      } catch (PDOException $p) {
         phpAlert('', $p);
      }
      $yieldr="Select unit, sum(yield)/(Select sum(tft) from 
         ((Select bedft * rowsBed as tft from dir_planted where fieldID like '".$fieldID.
         "' and year(plantdate) between '".$year."' and '".$tcurYear."' and 
         dir_planted.crop= '".$crop."' and gen like '".$genSel."') union all 
         (Select bedft * rowsBed as tft from transferred_to where year(transdate) between '".$year."' and '".
            $tcurYear."' and transferred_to.crop= '".$crop.
            "' and fieldID like '".$fieldID."' and gen like '".$genSel."')) as temp1) as yperft from harvested where hardate between '".
            $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
            "' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
            $fieldID."' and gen like '".$genSel."' group by unit order by unit";
      try {
         $res3=$dbcon->query($yieldr);
      } catch (PDOException $p) {
         phpAlert('', $p);
      }
      $yielda="Select unit, sum(yield)/(Select sum(tft) from 
         ((Select bedft * size / (length * numberOfBeds) as tft from ".
         "dir_planted, field_GH where dir_planted.fieldID = field_GH.fieldID ".
         " and dir_planted.fieldID like '".$fieldID.
         "' and year(plantdate) between '".$year."' and '".$tcurYear."' and 
         dir_planted.crop= '".$crop."' and gen like '".$genSel."') union all 
         (Select bedft * size / (length * numberOfBeds) as tft from ".
            "transferred_to, field_GH where transferred_to.fieldID = field_GH.fieldID ".
            " and year(transdate) between '".$year."' and '".
            $tcurYear."' and transferred_to.crop= '".$crop.
            "' and transferred_to.fieldID like '".$fieldID."' and gen like '".$genSel."')) as temp1) as yperft from harvested where hardate between '".
            $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
            "' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
            $fieldID."' and gen like '".$genSel."' group by unit order by unit";
      try {
         $resa=$dbcon->query($yielda);
      } catch (PDOException $p) {
         phpAlert('', $p);
      }
      echo "<table class='pure-table pure-table-border'>";
      echo "<thead><tr><th>Total Yield</th>";
      echo "<th>Average Yield (bed feet)</th>";
      echo "<th>Average Yield (row feet)</th><th>Average Yield (acres)</t>";
      echo "<th>Hours</th><th>Hours/Unit</th></tr></thead>";
      while ($row1 = $res->fetch(PDO::FETCH_ASSOC)) {
          $row2 = $res2->fetch(PDO::FETCH_ASSOC);
          $row3 = $res3->fetch(PDO::FETCH_ASSOC);
          $rowa = $resa->fetch(PDO::FETCH_ASSOC);
          echo "<tr><td>".number_format((float) $row1['total'], 2, '.', '')
             ." ".$row1['unit']."(S)</td>";
          $row2Deci3=number_format((float)$row2['yperft'], 3, '.', '');
          echo "<td>".$row2Deci3." ".$row2['unit']."(S)/Bed Foot</td>";
          $row2Deci3=number_format((float)$row3['yperft'], 3, '.', '');
          echo "<td>".$row2Deci3." ".$row3['unit']."(S)/Row Foot</td>";
          $rowaDeci=number_format((float)$rowa['yperft'], 3, '.', '');
          echo "<td>".$rowaDeci." ".$row3['unit']."(S)/Acre</td>";
          echo "<td>".number_format((float) $row1['hours'], 2, '.', '')."</td>";
          if ($row1['total'] > 0) {
             echo "<td>".number_format((float) $row1['hours'] / $row1['total'],                  2, '.', '')."</td>";
          } else {
             echo "<td>N/A</td>";
          }
          echo "</tr>";
      }
      echo "</table>";
      echo "<br clear = 'all'>";
   }
echo "<div class='pure-g'>";
echo "<div class='pure-u-1-2'>";
echo "<form name='form' method='POST' action='/down.php'>";
echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Download Report">';
echo "</form>";
echo "</div>";
echo "<div class='pure-u-1-2'>";
echo '<form method="POST" action = "harvestReport.php?tab=harvest:harvestReport"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo "</div>";
echo "</div>";
echo "<div class='pure-u-1-2'>";
echo "<div class='pure-u-1-2'>";
?>
