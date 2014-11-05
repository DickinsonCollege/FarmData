<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT TRateMin, TRateMax, TRateDefault,(TRateMax-TRateMin)/10 AS dif  FROM sprayMaterials  where sprayMaterial='".$_GET['material']."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
//echo mysql_error();
$ind=$row['TRateMin'];
echo "<option value=\"".$row['TRateDefault']."\">".$row['TRateDefault']."</option> \n";

while($ind<=$row['TRateMax']){
echo "<option value=\"".$ind."\">".$ind."</option> \n";
$ind=$ind+$row['dif'];
}


}
?>

