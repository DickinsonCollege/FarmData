<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM fertilizer WHERE id=".$_GET['id'];
      mysql_query($sqlDel) or die(mysql_error());
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $crops = escapehtml($_GET['crop']);
   $material = escapehtml($_GET['material']);
   $sql = "select id, username, inputDate, fieldID, fertilizer, crops, rate, numBeds, totalApply, comments ".
      "from fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and crops like '%".
      $crops."%' and fertilizer like '".$material."' order by inputDate";
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_GET['fieldID'];
   } 
   if( $crops == "%") {
      $grp = "All Crops";
   } else {
      $grp = $_GET['crop'];
   } 
   if( $material == "%") {
      $mat = "All Materials";
   } else {
      $mat = $_GET['material'];
   } 
   echo "<caption> Dry Fertilizer Application Report for ".$mat." on ".$grp." in Field: ".$fld."  </caption>";
   
   echo "<tr><th>Date</th><th>Field</th><th>Material</th><th>Crops</th><th>Application Rate<br>".
     "(lbs/acre)</th><th>Number of Beds</th><th>Total Material Applied</th><th>Comments</th>";
   if ($_SESSION['admin']) {
      echo "<th>User</th><th>Edit</th><th>Delete</th>";
   }
   echo "</tr>";
   while ($row = mysql_fetch_array($sqldata)) {
      echo "<tr><td>";
      echo $row['inputDate'];
      echo "</td><td>";
      echo $row['fieldID'];
      echo "</td><td>";
      echo $row['fertilizer'];       
      echo "</td><td>";
      echo $row['crops'];
      echo "</td><td>";
      echo $row['rate'];
      echo "</td><td>";
      echo $row['numBeds'];
      echo "</td><td>";
      echo $row['totalApply'];
      echo "</td><td>";
      echo $row['comments'];
      echo "</td>";
      if ($_SESSION['admin']) {
         echo "<td>".$row['username']."</td>";
         echo "<td><form method=\"POST\" action=\"fertilizerEdit.php?month=".$month."&day=".$day.
            "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".$_GET['fieldID']."&crop=".$_GET['crop']."&material=".$_GET['material'].
           "&tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report\">";
         echo "<input type=\"submit\" name=\"submitEdit\" class=\"editbutton\" value=\"Edit\"></form></td>";
         echo "<td><form method=\"POST\" action=\"fertTable.php?month=".$month."&day=".$day.
            "&year=".$year."&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
            "&fieldID=".$_GET['fieldID']."&crop=".$_GET ['crop']."&material=".$_GET['material'].
           "&tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report\">";
         echo "<input type=\"submit\" name=\"submit\" class=\"deletebutton\" value=\"Delete\"";
         echo "onclick='return warn_delete();'></form>";
         echo "</td>";
      }
      echo "<tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($material != "%") {
      $total="Select sum(totalApply) as total from fertilizer where inputDate between '".$year."-".$month.
         "-".$day."' AND '".$tcurYear."-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID.
         "' and crops like '".  $crops."' and fertilizer like '".$material."'";

      $result=mysql_query($total) or die(mysql_error());
      while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total ".$material." Applied:&nbsp;</label>";
	echo "<input disabled class='textbox2' style='width: 120px;' type ='text' value=".
          number_format((float)$row1['total'], 2, '.', '').">";
        echo "<label>&nbsp; POUNDS</label>";
        echo '<br clear="all"/>';
     }
     echo '<br clear="all"/>';
  }

   echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
   echo "</form>";
echo '<form method="POST" action = "fertReport.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';

?>
</div>
