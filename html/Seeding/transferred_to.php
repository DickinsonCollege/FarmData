<?php session_start();?>
<!DOCTYPE html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<form name='form' class='pure-form pure-form-aligned' id='transform' method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>?tab=seeding:transplant:transplant_input">
<center>
<h2 class="hi"> Transplant Data Input Form </h2>
</center>
<fieldset>
<div class="pure-control-group">
<label for="transferred"> Date of Transplant: </label>
<?php
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
if (isset($_POST['fieldID'])) {
   $field = escapehtml($_POST['fieldID']);
}
//include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<?php
include 'fieldID_trans.php';
?>
<div class="pure-control-group">
<label for="fieldcrop">Name of Field:</label>
<select name="fieldID" id= "fieldID" class='mobile-select'>
<?php
echo '<option disabled value = 0  style="display:none; width: auto;" ';
if (!isset($field)) {
   echo 'selected';
}
echo '> Field Name</option>';
$result=$dbcon->query("Select fieldID from field_GH where active = 1");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
  $fieldID = $row1['fieldID'];
  echo "\n<option value= \"".$fieldID."\"";
  if (isset($field) && $field == $fieldID) {
   echo ' selected';
  }
  echo ">".$fieldID."</option>";
}
?>
</select>
</div>

<div class="pure-control-group">
<?php
$farm = $_SESSION['db'];
if (!$_SESSION['bedft']) {
   echo '<label for="bed">Beds Planted:</label>';
} else {
  echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
  echo '<option value = "bed" selected>Bed Feet Planted: </option>';
  echo '<option value = "row">Row Feet Planted: </option>';
  echo '</select> ';
  echo ' &nbsp; ';
}
?>
<input class="textbox2 mobile-input single_table" type="text" name ="bedftv" value= 0 id="bedftv">
</div>
<div class="pure-control-group">
<label for="rows">Rows per Bed:</label>
<select name ="rowbd" id="rowbd" class="mobile-select">  
<option selected="selected" value = 1>1 </option>
<?php
$cons=2;
while ($cons<8) {
    if ($cons != 6) {
        echo "\n<option value =\"$cons\">$cons</option>";
    }
   $cons++;
}

?>
</select>
</div>
<div class="pure-control-group">
<label for="numFlats">Number of trays:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" name="numFlats" id="numFlats" class="textbox2 mobile-input single_table">
</div>

<?php
if ($_SESSION['gens']) {
   echo '<div class="pure-control-group" id="genDiv">';
   echo '<label for="gen">Succession #:</label> ';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   $sql = "select distinct gen from gh_seeding order by gen";
   try {
      $res = $dbcon->query($sql);
   } catch (PDOException $p) {
      die($p->getMessage());
   }
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
     $i = $row['gen'];
     echo "\n<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
}

if ($_SESSION['labor']) {
   echo '<div class="pure-control-group">';
   echo '
<label for="numWorkers">Number of workers:</label>
<input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input single_table">
  </div>

   <div class="pure-control-group">
<label>Enter time in Hours or Minutes:</label>
<input onkeypress=\'stopSubmitOnEnter(event)\'; type="text" name="time" id="time" value="1"
  class="textbox2 mobile-input-half single_table">
<select name="timeUnit" id="timeUnit" class=\'mobile-select-half single_table\'>
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div>';
}
?>

<div class="pure-control-group">
<label for="comments">Comments:</label>
<textarea name ="comments"
rows="5" cols= "30">
</textarea>
</div>
<br clear="all"/>
</fieldset>
<div class="pure-g">
<div class="pure-u-1-2">
<input class="submitbutton pure-button wide" type="button" value="Submit"  onclick="show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<?php
echo '<form method="POST" action = "/Seeding/transplantReport.php?tab=seeding:transplant:transplant_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>';
?>
</div>
</div>


<script type="text/javascript">
function show_confirm() {
   var crp = document.getElementById("cropButton").value;
   if (checkEmpty(crp) || crp=="Crop") {
      showError("Please Select A Crop");
      return false;
   }
   var con="Crop: "+ crp + "<br>";
   var annual = document.getElementById("annual").value;
   if (annual == 1) {
      con += "Annual: yes<br>";
   } else {
      con += "Annual: no<br>";
      var lastYear = document.getElementById("lastYear").value;
      con += "Last Harvest: " + lastYear + "<br>";
   }
   var sd = document.getElementById("seedDate").value;
   if (checkEmpty(sd)) {
      showError("Please Select A Crop that Has Been Seeded");
      return false;
    }
    con += "Seed Date: "+ sd + "<br>";
    var fld = document.getElementById("fieldID").value;
    if (checkEmpty(fld)) {
      showError("Please Select A Field Name");
      return false;
    }
    con += "Name of Field: "+ fld + "<br>";
    var mth = document.getElementById("month").value;
    var day = document.getElementById("day").value;
    var year = document.getElementById("year").value;
    con += "Date of Transplant: "+ year + "-" + mth + "-" + day + "<br>";

    var bf = document.getElementById("bedftv").value;
    var r = document.getElementById("rowbd").value;
    var rowBed = document.getElementById("rowBed");

     if (checkEmpty(r)) {
        showError("Please Select the Number of Rows per Bed");
        return false;
     }
     var div = 1;
     if (rowBed && rowBed.value == "row") {
        div = r;
     }
     var bed = <?php if (!$_SESSION['bedft']) {
        echo '"Number of Beds";';
     } else {
        echo '"Number of Bed Feet";';
     } ?>
     if (checkEmpty(bf) || isNaN(bf) || bf<=0) {
        showError("Please enter valid "+bed+"!");
        return false;
     }
     con += bed + ": "+ bf + "<br>";
     con += "Rows/Bed: "+ r + "<br>";

     var numF = document.getElementById("numFlats").value;
     if (checkEmpty(numF) || numF<=0 || !isFinite(numF)) {
         showError("Please enter a valid number of trays!");
         return false;
     }
     con += "Number of trays: "+ numF+ "<br>";

<?php
  include $_SERVER['DOCUMENT_ROOT'].'/Seeding/checkGen.php';
  if ($_SESSION['labor']) {
    echo '
     var numW = document.getElementById("numW").value;
     if (checkEmpty(numW) || numW<=0 || !isFinite(numW)) {
        showError("Enter a valid number of workers!");
        return false;
     }
     con += "Number of workers: "+ numW+ "<br>";

     var tme = document.getElementById("time").value;
     var unit = document.getElementById("timeUnit").value;
     if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
        showError("Enter a valid number of " + unit + "!");
        return false;
     }
     con += "Number of " + unit + ": " + tme + "<br>";';
  }
?>

   // return confirm("Confirm Entry:"+"<br>"+con);
   var msg = "Confirm Entry:"+"<br>"+con;
   showConfirm(msg, 'transform');
}
</script>
<?php
// if(isset($_POST['submit'])) {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $comSanitized=escapehtml($_POST['comments']);
   $bedftv = escapehtml($_POST['bedftv']);
   $numrows = escapehtml($_POST['rowbd']);
   if ($_SESSION['bedftv'] && $_POST['rowBed'] == "row") {
      $bedftv = $bedftv / $numrows;
   }

   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   $numFlats = escapehtml($_POST['numFlats']);
   $user = escapehtml($_SESSION['username']);
   if (!$_SESSION['bedft']) {
      $sql = "select length from field_GH where fieldID = '".$fld."'";
      $result = $dbcon->query($sql);
      $row= $result->fetch(PDO::FETCH_ASSOC);
      $len = $row['length'];
      $bedftv = $bedftv * $len;
   }

   if ($_SESSION['labor']) {
      // Check if given time is in minutes or hours
      $time = escapehtml($_POST['time']);
      if ($_POST['timeUnit'] == "minutes") {
         $hours = $time/60;
      } else if ($_POST['timeUnit'] == "hours") {
         $hours = $time;
      }
      // Check if num workers is filled in
      $numW = escapehtml($_POST['numW']);
      if ($numW != "") {
         $totalHours = $hours * $numW;
      } else {
         $totalHours = $hours;
      }
   } else {
      $totalHours = 0;
   }
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
   $annual = $_POST['annual'];
   if ($annual == 1) {
      $lastYear = $_POST['year'];
   } else {
      $lastYear = $_POST['lastYear'];
   }

   $dbcon->query("SET SESSION sql_mode = 'ALLOW_INVALID_DATES'");
   $sql="INSERT INTO transferred_to(username,fieldID,crop,seedDate,transdate,bedft,rowsBed,flats,gen,".
      "hours,comments,annual,lastHarvest) VALUES ('".
      $user."','".$fld."','".$crop."','".$_POST['seedDate']."','".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."',".$bedftv.
      ", ".$numrows.", ".$numFlats.", ".$gen.", ".$totalHours.",'".$comSanitized."', ".$annual.
      ", '".$lastYear."-12-31')";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
       phpAlert('Could not enter transplant data', $p);
       die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";

}   
?>
