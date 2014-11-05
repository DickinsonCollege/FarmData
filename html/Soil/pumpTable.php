<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$farm = $_SESSION['db'];
?>
<form name='form' method='POST' action='/down.php'>
<?php
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
$tcurYear = $_POST['tyear'];
$tcurMonth = $_POST['tmonth'];
$tcurDay = $_POST['tday'];
$start = $year."-".$month."-".$day;
$end = $tcurYear."-".$tcurMonth."-".$tcurDay;
$sql="select pumpDate, run_time/60 as run_time, driveHZ, pump_kwh, solar_kwh, comment ".
   " from pump_master where pumpDate between '". 
   $start."' AND '".$end."' order by pumpDate";
$result=mysql_query($sql);
if(!$result){
    echo "<script>alert(\"Could not Generate Pump Log Report: Please try again!\\n".mysql_error()."\");</script>\n";
}
echo '<input type="hidden" value="'.escapehtml($sql).'" name = "query" id="query">';
echo "<table >";
echo "<caption> Pump Log Report from ".$start." to ".$end."</caption>";

echo "<tr><th>Date</th><th>Run Time (Hours)</th><th>Drive Hz</th><th>Pump KWH</th>";
if ($farm == "dfarm") {
   echo "<th>Solar KWH</th>";
}
echo "<th>&nbsp;&nbsp;&nbsp;&nbsp;Comment</th></tr>";
while ( $row = mysql_fetch_array($result)) {
        echo "<tr><td>";
        //echo str_replace("-","/",$row['sDate']);
	echo $row['pumpDate'];
        echo "</td><td>";
        echo number_format((float) $row['run_time'], 2, '.', '');
        echo "</td><td>";
        echo $row['driveHZ'];
        echo "</td><td>";
        echo $row['pump_kwh'];
        echo "</td><td>";
if ($farm == "dfarm") {
        echo $row['solar_kwh'];
	echo "</td><td>";
}
	echo $row['comment'];
        echo "</td></tr>";
}
echo "</table>";
echo '<br clear="all"/>';
        echo '<input type="submit" name="submit" class="submitbutton" value="Download Report">';
echo "</form>";
echo '<form method="POST" action = "weedReport.php?tab=soil:soil_scout:soil_weed:weed_report"><input type="submit" class="submitbutton" value = "Run Another Report"></form>';
?>
