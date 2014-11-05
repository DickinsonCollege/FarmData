<?php session_start();?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>

<form name='form' method='POST' action='/down.php'>
<?php
if(isset($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $crop = escapehtml($_POST["crop"]);
   $fieldID = escapehtml($_POST["fieldID"]);
#   $sql = "SELECT hardate,fieldID, harvested.crop,yield,units,hours,comments FROM harvested,plant where hardate BETWEEN '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and harvested.crop like '" .$_POST["crop"] ."' and plant.crop = harvested.crop and fieldID like '".$_POST['fieldID']."' order by hardate";
   $sql = "SELECT hardate,fieldID, harvested.crop,yield,unit,hours,comments FROM harvested where hardate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and harvested.crop like '" .$crop."' and fieldID like '".
      $fieldID."' order by hardate";
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   $sqldata = mysql_query($sql) or die("ERROR");
   echo "<table>";
   $crp = $_POST["crop"];
   if($fieldID == "%" && $crp == "%") {
      echo "<caption> Harvest Report for All Crops in All Fields </caption>";
   } else if ($crp != "%" && $fieldID == "%") {
      echo "<caption> Harvest Report for ".$crp." in All Fields </caption>";
   } else if ($crp != "%" && $fieldID != "%") {
      echo "<caption> Harvest Report for ".$crp." in Field ".$fieldID.
           " </caption>";
   }else  {
      echo "<caption> Harvest Report for All Crops in Field ".$fieldID.
      " </caption>";
   }
   echo "<tr><th>Date</th><th>Field</th><th>Crop</th><th>Yield</th><th>Unit</th>";
   if ($_SESSION['labor']) {
      echo "<th>Hours</th>";
   }
   echo "<th> &nbsp;  Comments  </th></tr>";
   while($row = mysql_fetch_array($sqldata)) {
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
      if ($_SESSION['labor']) {
         echo number_format((float) $row['hours'], 2, '.', '');
         echo "</td><td>";
      }
      echo $row['comments'];
      echo "</td></tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($crop != "%") {
      $total="Select sum(yield) as total, unit from harvested where hardate between '".
         $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
         "' and harvested.crop like '" .$crop.
         "' and harvested.fieldID like '".$fieldID."' group by unit order by unit";
      $res=mysql_query($total);
      echo mysql_error();
      $yield="Select unit, sum(yield)/(Select sum(tft) from 
         ((Select bedft as tft from dir_planted where fieldID like '".$fieldID.
         "' and year(plantdate) between '".$year."' and '".$tcurYear."' and 
         dir_planted.crop= '".$crop."') union all 
         (Select bedft as tft from transferred_to where year(transdate) between '".$year."' and '".
            $tcurYear."' and transferred_to.crop= '".$crop.
            "' and fieldID like '".$fieldID."')) as temp1) as yperft from harvested where hardate between '".
            $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
            "' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
            $fieldID."' group by unit order by unit";
      $res2=mysql_query($yield);
      echo mysql_error();
      $yieldr="Select unit, sum(yield)/(Select sum(tft) from 
         ((Select bedft * rowsBed as tft from dir_planted where fieldID like '".$fieldID.
         "' and year(plantdate) between '".$year."' and '".$tcurYear."' and 
         dir_planted.crop= '".$crop."') union all 
         (Select bedft * rowsBed as tft from transferred_to where year(transdate) between '".$year."' and '".
            $tcurYear."' and transferred_to.crop= '".$crop.
            "' and fieldID like '".$fieldID."')) as temp1) as yperft from harvested where hardate between '".
            $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay.
            "' and harvested.crop = '" .$crop."' and harvested.fieldID like '".
            $fieldID."' group by unit order by unit";
      $res3=mysql_query($yieldr);
      echo mysql_error();
      echo "<table>";
      echo "<tr><th>Total Yield</th>";
      echo "<th>Average Yield (bed feet)</th>";
      echo "<th>Average Yield (row feet)</th></tr>";
      while ($row1 = mysql_fetch_array($res)) {
          $row2 = mysql_fetch_array($res2);
          $row3 = mysql_fetch_array($res3);
          echo "<tr><td>".number_format((float) $row1['total'], 2, '.', '')
             ." ".$row1['unit']."(S)</td>";
          $row2Deci3=number_format((float)$row2['yperft'], 3, '.', '');
          echo "<td>".$row2Deci3." ".$row2['unit']."(S)/Bed Foot</td>";
          $row2Deci3=number_format((float)$row3['yperft'], 3, '.', '');
          echo "<td>".$row2Deci3." ".$row3['unit']."(S)/Row Foot</td></tr>";
      }
      echo "</table>";
      echo "<br clear = 'all'>";
   }
}
        echo '<input class="submitbutton" type="submit" name="submit" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "harvestReport.php?tab=harvest:harvestReport"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
