<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
echo '<br clear="all"/>';
if(isset($_GET['submit'])) {
if(isset($_GET['id'])){
   $sqlDel="DELETE FROM coverKill_master WHERE id=".$_GET['id'];
   mysql_query($sqlDel);
   echo mysql_error();
	$sqlDel="DELETE FROM coverKill WHERE id=".$GET['id'];
	mysql_query($sqlDel);
	echo mysql_error();
}
if(!empty($_GET['fieldID'])) {
   $year = $_GET['year'];
   $month = $_GET['month'];
   $day = $_GET['day'];
   $tcurYear = $_GET['tyear'];
   $tcurMonth = $_GET['tmonth'];
   $tcurDay = $_GET['tday'];
   $origFieldID = escapehtml($_GET['fieldID']);
	$sql = "SELECT killDate, seedDate, incorpTool, totalBiomass, comments, fieldID, id, 
		totalBiomass/(SELECT size FROM field_GH WHERE fieldID=coverKill_master.fieldID) as bioPerAcre 
		FROM coverKill_master 
		WHERE killDate between '".$year."-".$month."-".$day."' AND '".$tcurYear."-".$tcurMonth."-".$tcurDay."' 
		AND fieldID LIKE '".$origFieldID."' 
		ORDER BY killDate";
   $result=mysql_query($sql);
   if(!$result){
    echo "<script>alert(\"Could not retrieve data: Please try again!\\n".mysql_error()."\");</script>\n";
   }
} else {
    echo    "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
}
echo '<input type="hidden" value="'.$sql.'" name = "query" id="query">';
echo "<table border>";
if($origFieldID != "%") {
   echo "<caption> Incorporation Records for: Field ".$origFieldID."</caption>";
} else {
   echo "<caption> Incorporation Records for All Fields </caption>";
}
echo "<tr><th>Kill Date</th><th>Cover Crops</th><th>Seed Date</th><th> Field </th><th>Incorporation Tool</th><th>Total Biomass</th><th> Biomass Pounds Per Acre </th><th>Comments</th><th>Edit</th><th>Delete</th></tr>";
while ( $row = mysql_fetch_array($result)) {
	$var=number_format($row['bioPerAcre'],2,'.','');
	$allCropsQuery = "SELECT coverCrop FROM coverKill WHERE id=".$row['id'];
	$cropResult = mysql_query($allCropsQuery);
	$cropString = "";
	$count = 1;
	while ($cropRow = mysql_fetch_array($cropResult)) {
		$cropString .= $cropRow['coverCrop'];
		if (mysql_num_rows($cropResult) > $count) {
			$cropString .= "<br/>";
		}
		$count++;
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
	echo $var;
	echo "</td><td>";
        echo $row['comments'];
        echo "</td>";

		echo "<td><form method='POST' action=\"incorpEdit.php?month=".$month."&day=".$day."&year=".$year.
			"&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
                  "&fieldID=".encodeURIComponent($_GET['fieldID']).
			"&tab=admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverincorp&submit=Submit\">";
		echo "<input type='submit' class='editbutton' value='Edit'></form></td>";

		echo "<td><form method='POST' action=\"incorpTable.php?month=".$month."&day=".$day."&year=".$year.
			"&tmonth=".$tcurMonth."&tyear=".$tcurYear."&tday=".$tcurDay."&id=".$row['id'].
                        "&fieldID=".encodeURIComponent($_GET['fieldID']).
			"&tab=admin:admin_delete:deletesoil:deletefert:deletecover:deletecoverincorp&submit=Submit\">";
		echo "<input type='submit' class='deletebutton' value='Delete'></form></td>";

	echo "</tr>";
	echo "\n";
}
echo "</table>";
}
?>


