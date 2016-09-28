<?php

include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$crop = escapehtml($_GET['crop']);

if ($_GET['method']=="DRILL") {
    $sql="Select drillRateMin,drillRateMax from coverCrop where crop='".$crop."'";
    $result=$dbcon->query($sql);
    while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
        $min=$row['drillRateMin'];
        $max=$row['drillRateMax'];
    }
}else {
    $min = 0;
    $max = 0;
    $sql="Select brcstRateMin,brcstRateMax from coverCrop where crop='".$crop."'";
    $result=$dbcon->query($sql);
    while ($row=$result->fetch(PDO::FETCH_ASSOC)) {
        $min=$row['brcstRateMin'];
        $max=$row['brcstRateMax'];
    }
}
$min2=$min;
$inc = ($max - $min) / 10;
if ($inc < 1) {
   $inc = 1;
}

while ($min2<=$max) {
    $min2Formated=number_format($min2,1,'.','');
    echo "<option value=".$min2.">".$min2Formated."</option>";
    $min2=$min2+$inc;
}

?>

