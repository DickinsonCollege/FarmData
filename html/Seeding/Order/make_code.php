<?php
$sql = "SELECT nextNum from toOrder where crop = '".$crop."' and year = ".$sYear;
$result = mysql_query($sql);
$num = 1;
if ($row = mysql_fetch_array($result)) {
    $num = $row['nextNum'];
} else {
   $sql = "insert into toOrder values('".$crop."', ".$sYear.", 0, 1)";
   $result = mysql_query($sql);
   echo mysql_error();
}
$sql = "update toOrder set nextNum = ".($num + 1)." where crop = '".$crop."' and year = ".$sYear;
$result = mysql_query($sql);
echo mysql_error();
$code = substr($crop, 0, 1);
$code .= substr($crop, strlen($crop) - 1, 1);
$code .= "-";
$code .= substr($sYear, 2, 2);
$code .= "-".$num."-";
if ($org == 1) {
   $code .= "OG";
} else {
   $code .= "UT";
};
?>

