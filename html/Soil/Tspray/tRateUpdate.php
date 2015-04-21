<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$sql="SELECT TRateMin, TRateMax, TRateDefault,(TRateMax-TRateMin)/10 AS dif FROM tSprayMaterials  where sprayMaterial='".
   escapehtml($_GET['material'])."'";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result)) {
//echo mysql_error();
$ind=$row['TRateMin'];
echo "<option value=".$row['TRateDefault'].">".$row['TRateDefault']."</option> \n";

$formatDif=number_format($row['dif'],2,'.','');
if ($formatDif <= 0.1) {
   $formatDif = 0.1;
}

while($ind<=$row['TRateMax']){
echo "<option value=\"".$ind."\">".$ind."</option> \n";
// echo $formatDif=number_format($row['dif'],2,'.','');
   $ind=$ind+$formatDif;
}


}
?>

