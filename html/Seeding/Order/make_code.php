<?php
if ($isCover) {
  $order = "coverToOrder";
} else {
  $order = "toOrder";
}
$sql = "SELECT nextNum from ".$order." where crop = '".$crop."' and year = ".$sYear;
$result = $dbcon->query($sql);
$num = 1;
if ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $num = $row['nextNum'];
} else {
   $sql = "insert into ".$order." values('".$crop."', ".$sYear.", 0, 1)";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      die($p->getMessage());
   }
}
$sql = "update ".$order." set nextNum = ".($num + 1)." where crop = '".$crop."' and year = ".$sYear;
try {
   $stmt = $dbcon->prepare($sql);
   $stmt->execute();
} catch (PDOException $p) {
  die($p->getMessage());
}
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

