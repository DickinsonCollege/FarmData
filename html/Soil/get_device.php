<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = $_GET['fieldID'];
$sqlCount = "select count(*) as num from pump_master, pump_field where pump_master.id=pump_field.id and ".
   "fieldID='".  $fieldID."'";
$res = $dbcon->query($sqlCount);
$row = $res->fetch(PDO::FETCH_ASSOC);
$num = $row['num'];
$sql = "select irr_device from pump_master, pump_field where pump_master.id=pump_field.id and fieldID='".
    $fieldID."' order by pumpDate desc limit 1";
if ($num > 0) {
   $result = $dbcon->query($sql);
   $row = $result->fetch(PDO::FETCH_ASSOC);
   echo '<option selected value = "'.$row['irr_device'].'">'.$row['irr_device'].'</option>';
} else {
   echo'<option value = 0 selected disabled> Device</option>';
}
$result=$dbcon->query("Select irrigation_device from irrigation_device");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
echo "<option value = \"".$row1['irrigation_device']."\">".$row1['irrigation_device']."</option>";
}
?>
