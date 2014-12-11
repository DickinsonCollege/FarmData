<?php session_start(); ?>
<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

if(isset($_GET['id'])){
   $sqlDel="DELETE FROM bspray WHERE id=".$_GET['id'];
   mysql_query($sqlDel);
   echo mysql_error();
}

?>
<?php
$year = $_GET['year'];
$month = $_GET['month'];
$day = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
if(!empty($_GET['sprayMaterial']) && !empty($_GET['fieldID'])) {
   $sql = "Select id, sprayDate, fieldID, water, materialSprayed, rate, totalMaterial, mixedWith, crops, comments from bspray where sprayDate between '"
   .$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' and fieldID like '".
   escapehtml($_GET['fieldID'])."'and materialSprayed like '".escapehtml($_GET['sprayMaterial']).
  "' order by sprayDate";
$sqldata = mysql_query($sql) or die(mysql_error());
echo '<br clear="all"/>';
echo "<table>";
echo "<colgroup><col width='10px' id='col1'/>";
echo "<col id='col2'/>";
echo "<col id='col3'/>";
echo "<col id='col4'/>";
echo "<col id='col5'/>";
echo "</colgroup>";
if($_GET['sprayMaterial'] =="%" && $_GET['fieldID'] == "%") {
echo "<caption> Backpack Spray Report for All Spray Materials on All Fields </ca
ption>";
} else if ($_GET['sprayMaterial'] != "%" && $_GET['fieldID'] == "%") {
echo "<caption> Backpack Spray Report for ".$_GET['sprayMaterial']." on All Fields </caption>";
} else{
echo "<caption> Backpack Spray Report for ".$_GET['sprayMaterial']." on Field: 
".$_GET['fieldID']." </caption>";
}
echo "<tr><th>Spray Date</th><th>Field ID</th><th>Water (Gallons)</th><th>Material Sprayed</th>".
   "<th>Rate</th><th>Total Material</th><th>Mixed With</th><th>Crops</th><th> Comments </th>".
   "<th>Edit</th><th>Delete</th></tr>";
while($row = mysql_fetch_array($sqldata)) {
	echo "<tr><td>";
	echo $row['sprayDate'];
	echo "</td><td>";
	echo $row['fieldID'];
	echo "</td><td>";
	echo $row['water'];       
	echo "</td><td>";
	echo $row['materialSprayed'];
	echo "</td><td>";
	echo $row['rate'];
	echo "</td><td>";
	echo $row['totalMaterial'];
	echo "</td><td>";
	echo $row['mixedWith'];
	echo "</td><td>";
	echo $row['crops'];
	echo "</td><td>";
	echo $row['comments'];
	echo "</td><td>";
	echo "<form method='POST' action=\"bsprayEdit.php?month=".$month."&day=".$day."&year=".$year.
           "&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".
           encodeURIComponent($_GET['fieldID']). "&sprayMaterial=".
           encodeURIComponent($_GET['sprayMaterial']).
           "&tab=admin:admin_delete:deletesoil:deletespray:deletebspray&submit=Submit\">";
	echo "<input type='submit' class='editbutton' value='Edit' /></form>";
	echo "</td><td>";
	echo "<form method='POST' action=\"bsprayTable.php?month=".$month."&day=".$day."&year=".$year.
           "&tmonth=".$tcurMonth.
           "&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id']."&fieldID=".
           encodeURIComponent($_GET['fieldID'])."&sprayMaterial=".
           encodeURIComponent($_GET['sprayMaterial']).
           "&tab=admin:admin_delete:deletesoil:deletespray:deletebspray&submit=Submit\">";
	echo "<input type='submit' class='deletebutton' value='Delete'/></form>";
	echo "</td></tr>";
	echo "\n";
}
	echo "</table>";
        echo '<br clear="all"/>';
}
?>
<br clear="all"/>
<!--
echo "<h3 class ='hi'> To generate another report click <a class='gx1' href='sprayReport2.php'> here </a></h3>";
-->
<form method="POST" action = "bsprayReport.php?tab=admin:admin_delete:deletesoil:deletespray:deletebspray">
<input type="submit" class="submitbutton" value = "Run Another Report">
</form>
</body>
</html>
