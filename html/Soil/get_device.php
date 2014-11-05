<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$fieldID = $_GET['fieldID'];
$sql = "select irr_device from pump_master, pump_field where pump_master.id=pump_field.id and fieldID='".
    $fieldID."' order by pumpDate desc limit 1";
$result = mysql_query($sql);
echo mysql_error();
$num = mysql_num_rows($result);
if ($num > 0) {
   $row = mysql_fetch_array($result);
   echo '<option selected value = "'.$row['irr_device'].'">'.$row['irr_device'].'</option>';
} else {
   echo'<option value = 0 selected disabled> Device</option>';
}
$result=mysql_query("Select irrigation_device from irrigation_device");
while ($row1 =  mysql_fetch_array($result)){
echo "<option value = \"".$row1['irrigation_device']."\">".$row1['irrigation_device']."</option>";
}
?>
