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
      mysql_query($sqlDel);
      echo mysql_error();
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
   $sql = "Select id, sprayDate, fieldID, water, materialSprayed, rate, BRateUnits, totalMaterial, mixedWith, crops, comments from bspray, tSprayMaterials where sprayDate between '".
        $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
        $fieldID."'and materialSprayed like '".$sprayMaterial."' and crops like '%".$crop.
        "%' and materialSprayed = sprayMaterial order by sprayDate";
      $sqldata = mysql_query($sql) or die(mysql_error());
      echo "<table>";
      echo "<colgroup><col width='10px' id='col1'/>";
      echo "<col id='col2'/>";
      echo "<col id='col3'/>";
      echo "<col id='col4'/>";
      echo "<col id='col5'/>";
      echo "</colgroup>";
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
      echo "<caption>Backpack Spray Report for ".$mat." on ".$fld."</caption>";
      echo "<tr><th>Spray Date</th><th>Field ID</th><th>Water (Gallons)</th><th>Material Sprayed</th><th>Rate</th><th>Total Material</th><th>Mixed With</th><th>Crops</th><th> Comments </th>";
      if ($_SESSION['admin']) {
         echo "<th>Edit</th><th>Delete</th>";
      }
      echo "</tr>";
      while($row = mysql_fetch_array($sqldata)) {
   echo "<tr><td>";
   //echo str_replace("-","/",$row['sprayDate']);
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
      echo "<input type='submit' class='editbutton' value='Edit' /></form>";
      echo "</td><td>";
      echo "<form method='POST' action=\"sprayTable.php?month=".$month.
         "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
         "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&sprayMaterial=".encodeURIComponent($_GET['sprayMaterial']).
         "&tab=soil:soil_spray:bspray:bspray_report\">";
     echo "<input type='submit' class='deletebutton' value='Delete'";
     echo "onclick='return warn_delete();'/></form>";
     echo "</td>";
  }
  echo "</tr>";
  echo "\n";
}
      echo "</table>";
      echo '<br clear="all"/>';
      $total="Select sum(water) as water, sum(totalMaterial) as total from bspray where sprayDate between '".$year."-".$month."-".$day.
         "' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and materialSprayed like '".$sprayMaterial."'" ;
      $result=mysql_query($total) or die(mysql_error());
      $other = "Select BRateUnits from tSprayMaterials where sprayMaterial like '".$sprayMaterial."'";
      $result2 = mysql_query($other) or die(mysql_error());
      $row2 = mysql_fetch_array($result2);
      while ($row1 = mysql_fetch_array($result) ) {
        echo "<label for='total'>Total Gallons of Water Used:&nbsp;</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['water'].">";
        if ($sprayMaterial != "%") {
           echo '<br clear="all"/>';
           echo "<label for='total'>Total Material Used:&nbsp;</label>";
      echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total'].">";
      echo "<label style='margin-top: 4px'for='unit'>&nbsp;".$row2['BRateUnits']."(S)</label>";
        }
      }
      echo '<br clear="all"/>';
      echo '<br clear="all"/>';
      echo "<form name='form' method='POST' action='/down.php'>";
      echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
      echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
      echo '</form>';
      echo '<form method="POST" action = "sprayReport.php?tab=soil:soil_spray:bspray:bspray_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
</div>
</body>
</html>
