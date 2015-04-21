<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if(isset($_GET['id'])){
      $sqlDel="DELETE FROM tillage WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
   }

   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   if(!empty($_GET['fieldID'])) {
      $sql = "Select id, tractorName, fieldID, tilldate, tool, num_passes, comment, minutes,  percent_filled from tillage where tilldate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
         $tcurDay."' and fieldID like '".$fieldID."'  order by tilldate";
      $sqldata = mysql_query($sql) or die(mysql_error());
      echo "<table>";
      if( $fieldID == "%") {
         echo "<caption> Tillage Report for All Fields </caption>";
      } else {
         echo "<caption> Tillage Report for Field: ".$_GET['fieldID']."  </caption>";
      } 
      echo "<tr><th>Tractor</th><th>Field ID</th><th>Tillage Date</th><th>Implement</th><th>Number of Passes</th><th>Comments</th><th>Minutes</th><th> Percent Tilled </th>";
     if ($_SESSION['admin']) {
        echo "<th>Edit</th><th>Delete</th>";
     }
     echo "</tr>";
   while($row = mysql_fetch_array($sqldata)) {
   echo "<tr><td>";
   echo $row['tractorName'];
   echo "</td><td>";
   echo $row['fieldID'];
   if(!$_SESSION['mobile']) {
           echo "</td><td style='width: 180px;'>";
        }
        //echo str_replace("-","/",$row['tilldate']);
   echo $row['tilldate'];       
        echo "</td><td>";
   echo $row['tool'];       
        echo "</td><td>";
   echo $row['num_passes'];
   echo "</td><td>";
   echo $row['comment'];
        echo "</td><td>";
   echo $row['minutes'];
   echo "</td><td>";
   echo $row['percent_filled']."%";
   echo "</td>";
   if ($_SESSION['admin']) {
     echo "<td><form method=\"POST\" action=\"tillageEdit.php?month=".$month.
        "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
        "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
        "&fieldID=".$_GET['fieldID'].
        "&tab=soil:soil_fert:soil_till:till_report\">";
     echo "<input type=\"submit\" class=\"editbutton\" value=\"Edit\"></form></td>";

     echo "<td><form method=\"POST\" action=\"tillageTable.php?month=".$month.
        "&day=".$day."&year=".$year."&tmonth=".$tcurMonth.
        "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
        "&fieldID=".$_GET['fieldID'].
        "&tab=soil:soil_fert:soil_till:till_report\">";
     echo "<input type=\"submit\" class=\"deletebutton\" value=\"Delete\"";
     echo "onclick='return warn_delete();'></form></td>";

   }
   echo "<tr>";
   echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   $total="Select sum(num_passes) as total, sum(minutes) as total2, avg(num_passes) as average, avg(minutes) as average2 from tillage where tilldate between '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."'" ;
   $result=mysql_query($total) or die(mysql_error());
   while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total Number of Passes:&nbsp;</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total'].">";
        echo '<br clear="all"/>';
        echo "<label for='total'>Total Minutes:&nbsp;</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row1['total2'].">";
        echo '<br clear="all"/>';
   $row3Deci3=number_format((float)$row1['average'], 1, '.', '');
        echo "<label for='total'>Average Number of Passes:&nbsp</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci3.">";
        echo '<br clear="all"/>';
   $row3Deci4=number_format((float)$row1['average2'], 1, '.', '');
        echo "<label for='total'>Average Minutes:&nbsp</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".$row3Deci4.">";
        echo '<br clear="all"/>';
  }
  echo '<br clear="all"/>';
  echo '<br clear="all"/>';
  echo "<form name='form' method='POST' action='/down.php'>";
  echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
  echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';

echo "</form>";
echo '<form method="POST" action = "tillageReport.php?tab=soil:soil_fert:soil_till:till_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
}

?>
</div>
</body>
</html>
