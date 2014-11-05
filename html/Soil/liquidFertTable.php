<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<form name='form' method='POST' action='/down.php'>
<?php
if(!empty($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $tcurYear = $_POST['tyear'];
   $tcurMonth = $_POST['tmonth'];
   $tcurDay = $_POST['tday'];
   $fieldID = escapehtml($_POST['fieldID']);
   //$group = escapehtml($_POST['group']);
   $material = escapehtml($_POST['material']);
   $sql = "select inputDate, fieldID, fertilizer, dripRows, unit, username, ".
      "quantity, comments ".
      "from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and fertilizer like '".$material."' order by inputDate";
      echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   $sqldata = mysql_query($sql) or die(mysql_error());
   echo "<table>";
   if( $fieldID == "%") {
      $fld = "All Fields";
   } else {
      $fld = $_POST['fieldID'];
   } 
   /*if( $group == "%") {
      $grp = "All Crop Groups";
   } else {
      $grp = $_POST['group'];
   }*/ 
   if( $material == "%") {
      $mat = "All Materials";
   } else {
      $mat = $_POST['material'];
   } 
   echo "<caption> Liquid Fertilizer Application Report for ".$mat." in Field: ".$fld."  </caption>";
   
   echo "<tr><th>Date</th><th>Field</th><th>Material</th><th>number of drip rows</th><th>unit<br>".
     "</th><th>Total Material Applied</th><th>Comments</th></tr>";
   while ($row = mysql_fetch_array($sqldata)) {
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
      echo "</td><tr>";
      echo "\n";
   }
   echo "</table>";
   echo '<br clear="all"/>';
   if ($material != "%") {
      $total = "select ".
 "sum(case when unit='QUARTS' then quantity / 4 else quantity end) as total ".
      "from liquid_fertilizer where inputDate between '".  $year."-".$month."-".$day."' AND '".$tcurYear.
      "-".$tcurMonth."-". $tcurDay."' and fieldID like '".$fieldID."' and fertilizer like '".$material."'";

      $result=mysql_query($total) or die(mysql_error());
      while ($row1 = mysql_fetch_array($result)  ) {
        echo "<label for='total'>Total ".$material." Applied:&nbsp;</label>";
	echo "<input disabled class='textbox2' style='width: 120px;' type ='text' value=".
          number_format((float)$row1['total'], 2, '.', '').">";
        echo "&nbsp; GALLONS";
        echo '<br clear="all"/>';
     }
     echo '<br clear="all"/>';
  }

  echo '<input type="submit" class="submitbutton mobile-submit" name="submit" value="Download Report">';

echo "</form>";
echo '<form method="POST" action = "liquidFertReport.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report"><input type="submit" class="submitbutton mobile-submit" value = "Run Another Report"></form>';
}

?>
</div>
