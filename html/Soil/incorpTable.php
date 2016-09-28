<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM coverKill WHERE id=".$_GET['id'];
      try {
         $stmt = $dbcon->prepare($sqlDel);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('', $p);
         die();
      }
      $sqlDel="DELETE FROM coverKill_master WHERE id=".$_GET['id'];
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
   $hiddensql = "SELECT killDate, (SELECT group_concat(coverCrop SEPARATOR '; ') FROM coverKill WHERE id=coverKill_master.id) as crops, 
      seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
      totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
      FROM coverKill_master 
      WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND fieldID LIKE '".$fieldID."' 
      ORDER BY killDate";
   $sql = "SELECT killDate, seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
      totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
      FROM coverKill_master 
      WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
      AND fieldID LIKE '".$fieldID."' 
      ORDER BY killDate";
   try {
      $result=$dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<center>";
   if($fieldID != "%") {
      echo "<h2> Incorporation Records for: Field ".$_GET['fieldID']."</h2>";
   } else {
      echo "<h2> Incorporation Records for All Fields </h2>";
   }
   echo "</center>";
   echo "<table class='pure-table pure-table-bordered'>";
   echo "<thead><tr><th>Kill Date</th><th>Cover Crop</th><th>Seed Date</th><th> Field </th><th>Incorporation Tool</th><th>Total Biomass</th><th> Biomass Pounds Per Acre </th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>Edit</th><th>Delete</th>";
   }
   echo "</tr></thead>";
   while ( $row = $result->fetch(PDO::FETCH_ASSOC)) {
      $allCropsQuery = "SELECT coverCrop FROM coverKill WHERE id=".$row['id'];
      $cropResult = $dbcon->query($allCropsQuery);
      $cropString = "";
      if ($cropRow = $cropResult->fetch(PDO::FETCH_ASSOC)) {
         $cropString .= $cropRow['coverCrop'];
      }
      while ($cropRow = $cropResult->fetch(PDO::FETCH_ASSOC)) {
         $cropString .= "<br>";
         $cropString .= $cropRow['coverCrop'];
      }
      echo "<tr><td>";
      echo $row['killDate'];
      echo "</td><td>";
      echo $cropString;
      echo "</td><td>";
      echo $row['seedDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['incorpTool'];
      echo "</td><td>";
      $row3Deci3=number_format((float)$row['totalBiomass'], 3, '.', '');
      echo $row3Deci3;
      echo "</td><td>";
      $var=number_format($row['bioPerAcre'], 2, '.', '');
      echo $var;
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td><form method='POST' action=\"incorpEdit.php?month=".$month."&day=".$day."&year=".$year.
            "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report\">";
         echo "<input type='submit' class='editbutton pure-button wide' value='Edit'></form></td>";
         echo "<td><form method='POST' action=\"incorpTable.php?month=".$month."&day=".$day."&year=".$year.
            "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".encodeURIComponent($_GET['fieldID']).
            "&tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report\">";
         echo "<input type='submit' class='deletebutton pure-button wide' value='Delete'";
         echo "onclick='return warn_delete();'></form></td>";
      }
      echo "</tr>";
   }
   echo "</table>";

   echo '<br clear="all"/>';
   echo '<div class="pure-form pure-form-aligned">';
   $sqlget = "Select sum(totalBiomass) as total, avg(totalBiomass) as average from coverKill_master where killDate between '"
       .$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
        $tcurDay."' and fieldID like '".$fieldID."'";
   $result = $dbcon->query($sqlget);
   while($row1 = $result->fetch(PDO::FETCH_ASSOC)) {
      echo "<div class='pure-control-group'>";
      echo "<label for='total'>Total Biomass:</label> ";
      echo "<input class='textbox2 mobile-input' readonly type='text' value=".$var=number_format($row1['total'],3,'.','').">";
      echo '</div>';
      $row3Deci3=number_format((float)$row1['average'], 3, '.', '');
      echo "<div class='pure-control-group'>";
      echo "<label for='total'>Average Biomass:</label> ";
      echo "<input class='textbox2 mobile-input' readonly type ='text' value=".$row3Deci3.">";
      echo '</div>';
      $row3Deci3=number_format((float)$row1['average'], 3, '.', '');
   }
echo '</div>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<div class="pure-g">';
echo '<div class="pure-u-1-2">';
echo "<form name='form' method='POST' action='/down.php'>";
echo '<input type="hidden" value="'.escapehtml($hiddensql).'" name = "query" id="query">';
echo '<input type="submit" class="submitbutton pure-button wide" name="submit" value="Download Report">';
echo "</form>";
echo '</div>';
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "incorpReport.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo '</div>';
echo '</div>';
?>


