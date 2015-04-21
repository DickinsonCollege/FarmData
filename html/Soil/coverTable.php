<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/warn.php';
?>
<?php
   if (isset($_GET['id'])) {
      $sqlDel="DELETE FROM coverSeed WHERE id=".$_GET['id'];
      mysql_query($sqlDel);
      echo mysql_error();
      $sqlDeleteMaster = 'Delete from coverSeed_master where id='.$_GET['id'];
      mysql_query($sqlDeleteMaster);
      echo mysql_error();
   }
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $fieldID = escapehtml($_GET['fieldID']);
   $sql = "SELECT id, seed_method,fieldID, ((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool, comments, seedDate FROM coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
$sqldata = mysql_query($sql);
if (!$sqldata) {
   echo "<script>alert(".mysql_error().");</script>";
}
$field = $_GET['fieldID'];
if ($_GET['fieldID']=='%'){
   $field='All';
}

echo "<table>";
echo "<caption> Cover Crop Seeding Report for Field: ".$field." </caption>";
echo "<tr><th style='width:45%;'>Date</th> <th>FieldID</th> <th>Seeding Method</th> <th>Area Seeded</th><th>Incorporation Tool</th><th style='width: 60%;' >Crop</th><th>Comments</th>";
if ($_SESSION['admin']) {
   echo "<th>Edit</th><th>Delete</th>";
}
echo "</tr>";
while($row = mysql_fetch_array($sqldata)) {
   $area=number_format($row['areaSeeded'],3,'.','');
   echo "<tr><td>";
   // echo str_replace("-","/",$row['seedDate']);
   echo $row['seedDate'];
   echo "</td><td>";
   echo $row['fieldID'];       
   echo "</td><td>";
   echo $row['seed_method'];
   echo "</td><td>";
   echo $area;
        echo "</td><td>";
   echo $row['incorp_tool'];
   echo "</td><td>";
   // query for coverSeed Table
   $sql = "select * from coverSeed where id=".$row[id]. " order by crop";
   $sqlCoverSeed = mysql_query($sql) or die(mysql_error());
   echo "<table style='width:100%'><tr><th>Crop</th><th>Seeding Rate (lbs/acre)</th><th style='width: 30%;'>Pounds Seeded</th></tr>";
   while ($rowS = mysql_fetch_array($sqlCoverSeed)){
      echo "<tr><td>".$rowS[crop]."</td><td>".$rowS[seedRate]."</td><td>".$rowS[num_pounds]."</td></tr>";
   }
   echo "</table>";
   echo "</td><td>";
   echo $row['comments'];
   echo "</td>";
   if ($_SESSION['admin']) {
      echo "<td><form method='POST' action=\"coverEdit.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tday=".$tcurDay."&tyear=".$tcurYear."&id=".$row['id'].
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report\">";
      echo "<input type='submit' class='editbutton' value='Edit'";
      echo 'onclick="return show_warning();">';
      echo "</form></td>";

      echo "<td><form method='POST' action=\"coverTable.php?month=".$month."&day=".$day."&year=".$year.
         "&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
         "&fieldID=".encodeURIComponent($_GET['fieldID']).
         "&tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report\">";
      echo "<input type='submit' class='deletebutton' value='Delete'";
      echo 'onclick="return show_delete_warning();">';
      echo "</form></td>";
   }
   echo "</tr>";
   echo "\n";
}
   echo "</table>";
$totalAreaSeeded = mysql_query("select sum(((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded) as totalSeeded from coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."'") or die(mysql_error());

$rowTotal = mysql_fetch_array($totalAreaSeeded);
echo "<label for='total'>Total Area Seeded (Acres):&nbsp</label>";
echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".number_format($rowTotal[totalSeeded],3,'.','').">";
echo "<br clear='all'>";
$totalByCrop = mysql_query("select crop, sum(num_pounds) as total from coverSeed natural join coverSeed_master where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' group by crop") or die(mysql_error());
while($rowCrop = mysql_fetch_array($totalByCrop)){
   echo "<label for='crop'>Total amount of ".$rowCrop[crop]." seeded:&nbsp</label>";
   echo "<input disabled class='textbox2 mobile-input' style='width: 120px;' type ='text' value=".number_format($rowCrop[total],3,'.','').">";
   echo "<br clear='all'>";
}
echo "<br clear=\"all\">";

$sql = "SELECT seedDate, seed_method,fieldID, ((Select size from field_GH where fieldID=coverSeed_master.fieldID)/100)*area_seeded as areaSeeded, incorp_tool,crop, seedRate, num_pounds, comments FROM coverSeed_master natural join coverSeed where seedDate BETWEEN '".
      $year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".
      $tcurDay."' and fieldID like '".$fieldID."' order by seedDate";
   echo "<form name='form' method='POST' action='/down.php'>";
   echo "<input type = \"hidden\" name = \"query\" value = \"".escapehtml($sql)."\">";
   echo '<input type="submit" class="submitbutton" name="submit" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "coverReport.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
</div>
