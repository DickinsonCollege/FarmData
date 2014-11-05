<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
//$fieldID = escapehtml($_GET['fieldID']);
//$percent = escapehtml($_GET['percent']);
$crop = escapehtml($_GET['crop']);
//$sql="Select ('".$percent."'/'100')*(Select size from field_GH where fieldID='".$fieldID."') as size";
//$area=mysql_query("Select ('".$percent."'/'100')*(Select size from field_GH where fieldID='".
  // $fieldID."') as size");
//while ($row=mysql_fetch_array($area)) {
//	$size=$row['size'];
//}

if ($_GET['method']=="DRILL") {
	$sql="Select drillRateMin,drillRateMax from coverCrop where crop='".$crop."'";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {
		$min=$row['drillRateMin'];
		$max=$row['drillRateMax'];
	}
}else {
$min = 0;
$max = 0;
$sql="Select brcstRateMin,brcstRateMax from coverCrop where crop='".$crop."'";
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {
		$min=$row['brcstRateMin'];
		$max=$row['brcstRateMax'];
	}
}
 $min2=$min;
$min2." ".$max;
while ($min2<=$max) {
	$min2Formated=number_format($min2,1,'.','');
	echo "<option value=".$min2.">".$min2Formated."</option>";
	$min2=$min2+(($max-$min)/10);

}

?>

