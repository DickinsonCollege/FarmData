<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

?>

<center>
<h2> Seed Order and Inventory </h2>
</center>

<form name='form' class='pure-form pure-form-aligned' id = 'seedform' method='POST' action='insertSeedInfo.php?tab=seeding:ordert:ordert_input'>
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
   try {
      $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      $seeds = $row['seedsGram'];
      $rowft = $row['seedsRowFt'];
      $defUnit = $row['defUnit'];
   }
   $sql = "select rowFt from toOrder where crop='".$crop."' and year = ".$year;
   try {
      $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      $rowftToPlant = $row['rowFt'];
   }
} else {
   $sql = "select * from coverSeedInfo where crop='".$crop."'";
   try {
      $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      $rate = $row['rate'];
   }
   $sql = "select * from coverToOrder where crop='".$crop."' and year=".$year;
   try {
   $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
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
echo "<fieldset>";
if (!$isCover) {
   echo '<div class="pure-control-group">';
   echo '<label for="rowft">Seeds per row foot:</label>';
   echo '<input class="textbox2 mobile-input single_table" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowft"';
   echo 'id="rowft" value="'.$rowft.'">';
   echo '</div>';
   echo '<div class="pure-control-group">';
   echo '<label>Seed unit:</label>';
   echo '<input style="width:12ex;" type="text" onkeypress="stopSubmitOnEnter(event);" ';
   echo 'name ="seedsIn" id="seedsIn" value="'.
     number_format((float) convertFromGram($defUnit, $seeds), 1, '.','').'"> ';
   // echo '<label for="seedsIn">seeds per</label>';
   echo '&nbsp;seeds /&nbsp;';
//   echo "<div id='defUnitdiv' class='styled-select'>";
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
   echo '<div class="pure-control-group">';
   echo '<label for="rowftToPlant">Total row feet';
   if (isset($crop)) { 
    echo " of ".$crop;
   }
   echo ' to plant';
   if (isset($year)) {
     echo " in ".$year;
   }
   echo ':</label>';
   echo '<input class="textbox2 mobile-input single_table" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rowftToPlant" ';
     echo 'id="rowftToPlant" value="'.$rowftToPlant.'">';
   echo '</div>';
} else {
   echo '<div class="pure-control-group">';
   echo '<label for="acres">Acres of '.$crop.' to plant in '.$year.':</label>';
   echo '<input class="textbox2 mobile-input single_table" type="text" onkeypress="stopSubmitOnEnter(event);" name ="acres"';
   echo 'id="acres" value="'.$acres.'">';
   echo '</div>';
   echo '<div class="pure-control-group">';
   echo '<label for="rate">Seeding rate for '.$crop.' (lbs/acre):</label>';
   echo '<input class="textbox2 mobile-input single_table" type="text" onkeypress="stopSubmitOnEnter(event);" name ="rate"';
   echo 'id="rate" value="'.$rate.'">';
   echo '</div>';
}
echo "</fieldset>";
echo '<br clear="all"/>';
echo '<input type="submit" name="updateSeedInfo" class = "submitbutton pure-button wide" ';
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

