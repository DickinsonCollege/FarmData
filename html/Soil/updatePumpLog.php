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
$sqlcheck = $dbcon->query('select count(*) as num from pump_log_temp');
$row = $sqlcheck->fetch(PDO::FETCH_ASSOC);
$numRows = $row['num'];
if ($numRows == 0){
   $sql = "insert into pump_log_temp values ('".$date."', trim('".$valve.
       "'), '".$drive."', '".$outlet."', '".$pump_kwh."', '".$solar_kwh."', '".
       $comment."',".time().")";
// file_put_contents("/tmp/log", $sql);
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      die($p->getMessage());
   }
} else {
   $sql = "update pump_log_temp set pumpDate='".$date."', valve_open=trim('"
      .$valve."'), driveHZ=".$drive.", outlet_psi=".$outlet.", pump_kwh='".
      $pump_kwh."', solar_kwh='".$solar_kwh."', comment='".$comment."'";
// file_put_contents("/tmp/log", $sql);
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      die($p->getMessage());
   }
}
?>
