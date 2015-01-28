<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

?>

<h3> Seed Order and Inventory </h3>

<form name='form' id = 'seedform' method='POST' action='insertSeedInfo.php?tab=seeding:ordert:ordert_input'>
<?php
if ($_POST['submitCrop']) {
   $crop = escapehtml($_POST['crop']);
   $year = $_POST['year'];
   $isCover = false;
   echo '<input type="hidden" id = "isCover" name="isCover" value="false">';
} else {
   $crop = escapehtml($_POST['cover']);
   $year = $_POST['coverYear'];
   $isCover = true;
   echo '<input type="hidden" id = "isCover" name="isCover" value="true">';
}
echo '<input type="hidden" id = "crop" name="crop" value="'.$crop.'">';
echo '<input type="hidden" id = "year" name="year" value="'.$year.'">';
?>
<br clear="all"/>
<?php
$seeds = "";
$rowft = "";
$defUnit = "";
$acres = "";
$rate = "";
if (!$isCover) {
   $sql = "select * from seedInfo where crop='".$crop."'";
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $seeds = $row['seedsGram'];
      $rowft = $row['seedsRowFt'];
      $defUnit = $row['defUnit'];
   }
   $sql = "select rowFt from toOrder where crop='".$crop."' and year = ".$year;
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $rowftToPlant = $row['rowFt'];
   }
} else {
   $sql = "select * from coverSeedInfo where crop='".$crop."'";
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $rate = $row['rate'];
   }
   $sql = "select * from coverToOrder where crop='".$crop."' and year=".$year;
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
      $acres = $row['acres'];
   }
}

function convertFromGram($unit, $seeds) {
   if ($unit == "GRAM") {
      $res = $seeds;
   } else if ($seeds == 0) {
      $res = 0;
   } else if ($unit == "OUNCE") {
      $res = $seeds * 28.3495;
   } else if ($unit == "POUND") {
      $res = $seeds * (28.3495 * 16);
   } else {
      $res = 0;
   }
   return $res;
}

   $units = array('GRAM', 'OUNCE', 'POUND');
if (!$isCover) {
   echo '<label for="rowft">Seeds per row foot:&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowft"';
   echo 'id="rowft" value="'.$rowft.'">';
   echo '<br clear="all"/>';
   echo '<input class="textbox25 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" ';
   echo 'name ="seedsIn" id="seedsIn" value="'.
     number_format((float) convertFromGram($defUnit, $seeds), 1, '.','').'"> ';
   echo '<label for="seedsIn">&nbsp; seeds per&nbsp;</label>';
   echo "<div id='defUnitdiv' class='styled-select'>";
   echo "<select name='defUnit' id='defUnit' class='mobile-select'>";
   for ($i = 0; $i < count($units); $i++) {
      echo "<option value='".$units[$i]."'";
      if ($units[$i] == $defUnit) {
         echo " selected";
      }
      echo ">".$units[$i]."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all"/>';
   echo '<label for="rowftToPlant">Total row feet';
   if (isset($crop)) { 
    echo " of ".$crop;
   }
   echo ' to plant';
   if (isset($year)) {
     echo " in ".$year;
   }
   echo ':&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowftToPlant" ';
     echo 'id="rowftToPlant" value="'.$rowftToPlant.'">';
} else {
   echo '<label for="acres">Acres of '.$crop.' to plant in '.$year.':&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="acres"';
   echo 'id="acres" value="'.$acres.'">';
   echo '<br clear="all"/>';
   echo '<label for="rate">Seeding rate for '.$crop.' (lbs/acre):&nbsp;</label>';
   echo '<input class="textbox2 mobile-input" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rate"';
   echo 'id="rate" value="'.$rate.'">';
   echo '<br clear="all"/>';
}
echo '<input type="submit" name="updateSeedInfo" class = "submitbutton" ';
echo ' value="Submit Seeding Information" onclick="return show_confirm();">';
?>
<script type="text/javascript">
function show_confirm() {
  <?php if ($isCover) {
    echo "var isCover = true;";
  } else {
    echo "var isCover = false;";
  } ?>
  var con="Crop: " + document.getElementById("crop").value + "\nYear: " + 
     document.getElementById("year").value + "\n";
  if (isCover) {
     var acres = document.getElementById("acres").value;
     if (checkEmpty(acres) || !isFinite(acres)) {
        alert("Enter a valid number of acres!");
        return false;
     } else {
        con += "Acres: " + acres + "\n";
     }
     var rate = document.getElementById("rate").value;
     if (checkEmpty(rate) || !isFinite(rate)) {
        alert("Enter a valid seeding rate!");
        return false;
     } else {
        con += "Seeding Rate: " + rate + " lbs/acre\n";
     }
  } else {
     var rowft = document.getElementById("rowft").value;
     if (checkEmpty(rowft) || !isFinite(rowft)) {
        alert("Enter a valid number of seeds per row foot!");
        return false;
     } else {
        con += "Seeds per row foot: " + rowft + "\n";
     }
     var seedsIn = document.getElementById("seedsIn").value;
     var defUnit = document.getElementById("defUnit").value;
     if (checkEmpty(seedsIn) || !isFinite(seedsIn)) {
        alert("Enter a valid number of seeds per " + defUnit + "!");
        return false;
     } else {
        con += "Seeds per " + defUnit + ": " + seedsIn + "\n";
     }
     var rowftToPlant = document.getElementById("rowftToPlant").value;
     if (checkEmpty(rowftToPlant) || !isFinite(rowftToPlant)) {
        alert("Enter a valid number of row feet to plant!");
        return false;
     } else {
        con += "Row feet to plant: " + rowftToPlant + "\n";
     }
  }
  return confirm("Confirm Entry:\n" + con);
}
</script>
</form>

