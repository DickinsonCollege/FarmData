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
echo "<center>";
echo "<h2> Pump Log Report from ".$start." to ".$end."</h2>";
echo "</center>";
echo "<table class='pure-table pure-table-bordered' >";

echo "<thead><tr><th>Date</th><th>Run Time (Hours)</th><th>Drive Hz</th><th>Pump KWH</th>";
if ($farm == "dfarm") {
   echo "<th>Solar KWH</th>";
}
echo "<th>&nbsp;&nbsp;&nbsp;&nbsp;Comment</th></tr></thead>";
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
echo '<div class="pure-g"><div class="pure-u-1-2">';
        echo '<input type="submit" name="submit" class="submitbutton pure-button wide" value="Download Report">';
echo "</form>";
echo '</div>';
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "pump.php?tab=soil:soil_irrigation:pump_report"><input type="submit" class="submitbutton pure-button wide" value = "Run Another Report"></form>';
echo '</div>';
echo '</div>';
?>
