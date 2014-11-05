<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$month = $_GET['month'];
$day    = $_GET['day'];
$year    = $_GET['year'];
$valve = escapehtml($_GET['valve']);
$drive = $_GET['drive'];
$outlet= $_GET['outlet'];
$pump_kwh = $_GET['pump_kwh'];
$solar_kwh= $_GET['solarKWH'];
$comment    = escapehtml($_GET['comment']);
$date    = $year.'-'.$month.'-'.$day;
$sqlcheck = mysql_query('select * from pump_log_temp');
if (mysql_num_rows($sqlcheck) == 0){
echo   $sql = "insert into pump_log_temp values ('".$date."', trim('".$valve.
       "'), '".$drive."', '".$outlet."', '".$pump_kwh."', '".$solar_kwh."', '".
       $comment."',".time().")";
// file_put_contents("/tmp/log", $sql);
   mysql_query($sql);
// or die(mysql_error());
   echo mysql_error();
} else {
   $sql = "update pump_log_temp set pumpDate='".$date."', valve_open=trim('"
      .$valve."'), driveHZ=".$drive.", outlet_psi=".$outlet.", pump_kwh='".
      $pump_kwh."', solar_kwh='".$solar_kwh."', comment='".$comment."'";
// file_put_contents("/tmp/log", $sql);
   echo $sql;
   mysql_query($sql);
// or die(mysql_error());
   echo mysql_error();
}
?>
