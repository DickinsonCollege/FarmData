<?php session_start();?>
<!DOCTYPE html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
// include $_SERVER['DOCUMENT_ROOT'].'/testPureMenu.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<div class="layout">

<script type="text/javascript">
function show_confirm() {
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
      showError("Please Select a Field Name");
      return false;
   }

   var con="Name of Field: "+ fld + "<br>";
   var crp = document.getElementById("cropButton").value;
   if (checkEmpty(crp) || crp == "Crop") {
      showError("Please Select a Crop");
      return false;
   }
   con += "Crop: "+ crp + "<br>";
<?php
if ($_SESSION['seed_order']) {
   echo '
   var count = 1;
   for (var i = 1; i <= numRows; i++) {
      if (document.getElementById("row" + i) != null &&
          document.getElementById("row" + i).innerHTML != "") {
         var code = document.getElementById("code" + i).value;
         if (checkEmpty(code)) {
            showError("Please select a seed code in row: " + count);
            return false;
         }
         count++;
      }
   }';
}
?>

   var mth = document.getElementById("month").value;
   con += "Date of Seeding: " + mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "<br>";
   var i = document.getElementById("bedftv").value;
   var r = document.getElementById("rowbd").value;
   if (checkEmpty(r)) {
      showError("Please Select Rows Per Bed");
      return false;
   } 
   var rowBed = document.getElementById("rowBed");
   var div = 1;
   if (rowBed && rowBed.value == "row") {
       div = r;
   }
   var bed = <?php if (!$_SESSION['bedft']) {
               echo '"Number of Beds";';
        } else { 
               echo '"Number of Bed Feet";';
         } ?>
   if (checkEmpty(i) || isNaN(i) || i<0) {
      showError("Enter valid "+bed+"!");
      return false;
   } 
   var con=con+bed+": "+ i + "<br>";

   con = con+"Rows/Bed: "+ r + "<br>";

<?php
  include $_SERVER['DOCUMENT_ROOT'].'/Seeding/checkGen.php';
  if ($_SESSION['labor']) {
     echo '
        var tme = document.getElementById("time").value;
   var unit = document.getElementById("timeUnit").value;
        if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
           showError("Enter a valid number of " + unit + "!");
           return false;
        }
   con = con+"Number of " + unit + ": " + tme + "<br>";';
   } 
?>

   var msg = "Confirm Entry:"+"<br>"+con;
   showConfirm(msg, 'seedform');
}
</script>

<form name='form' id='seedform' class='pure-form pure-form-aligned' method='post' 
  action="<?php echo $_SERVER['PHP_SELF']; ?>?tab=seeding:direct:direct_input">
<center>
<h2 class="hi"> Direct Seeding Input Form</h2>
</center>
<fieldset>
<div class="pure-control-group">
<label for="planted">Date of Seeding:</label>
<?php
if ($_SESSION['mobile']) echo "<br clear='all'/>";
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
if (isset($_POST['fieldID'])) {
   $field = escapehtml($_POST['fieldID']);
}
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for="fieldcrop">Name of Field:</label>
<select name="fieldID" id= "fieldID" class='mobile-select'>
<?php
echo '<option disabled value = 0  style="display:none; width: auto;" ';
if (!isset($field)) {
   echo 'selected';
}
echo '> Field Name</option>';
$result=mysql_query("Select fieldID from field_GH where active = 1");
while ($row1 =  mysql_fetch_array($result)){
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
<input type="hidden" name="numRows" id="numRows" value=0>
<div class="pure-control-group">
<label>Crop:</label>
<?php
echo '<select name="cropButton" id="cropButton" class="mobile-select">';
echo '<option disabled selected value="0">Crop</option>';
$sql = "select distinct crop from plant where active=1";
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) {
  echo '<option value="'.$row['crop'].'">'.$row['crop'].'</option>';
}
?>
</select></div>

<script type="text/javascript">
var numRows = 0;

function update_feet() {
   var tot = 0;
   for (var i = 1; i <= numRows; i++) {
      if (document.getElementById("row" + i) != null &&
          document.getElementById("row" + i).innerHTML != "") {
         tot += parseFloat(document.getElementById("bedftv" + i).value);
      }
   }
   document.getElementById("bedftv").value = tot;
}

function addRow() {
   var cb = document.getElementById("cropButton");
   if (cb.value=="0") {
      showError("Error: choose crop first!");
   } else {
      cb.disabled=true;
      numRows++;
      var nr = document.getElementById("numRows");
      nr.value = numRows;
      // var table = document.getElementById("seedTable");
      // var row = table.insertRow(numRows - 1);
      var table = document.getElementById("seedTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(numRows - 1);

      row.id = "row"+numRows;
      row.name = "row"+numRows;
      var cell0 = row.insertCell(0);
      var crop = encodeURIComponent(cb.value);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "get_code.php?crop="+crop, false);
      xmlhttp.send();
      if(xmlhttp.responseText=="\n") {
         cb.value="";
      }
      cell0.innerHTML="<div class='styled-select' id ='codediv" + numRows + "''>  <select name= 'code" +
         numRows + "' id= 'code" + numRows + "' class='mobile-select' style='width:100%'>" +
         xmlhttp.responseText+"</select> </div>";

      var cell1 = row.insertCell(1);
      cell1.innerHTML = "<input onkeypress= 'stopSubmitOnEnter(event);' type = 'text' name='bedftv" + numRows
         + "' id='bedftv"+numRows+"' class='textbox mobile-input inside_table' style='width:100%' " +
         "oninput='update_feet();' value='0'>";
   }
}

function removeRow() {
   if (numRows > 0) {
      var row = document.getElementById("row" + numRows);
      row.innerHTML = "";
      numRows--;
      update_feet();
   }
   if (numRows == 0) {
      var cb = document.getElementById("cropButton");
      cb.disabled=false;
   }
   var nr = document.getElementById("numRows");
   nr.value=numRows;
}
</script>

<?php
if ($_SESSION['seed_order']) {
   echo '<br clear = "all"/>';
   echo '<table id="seedTable" name="seedTable" class="pure-table pure-table-bordered">';
   echo '<thead><tr><th>Seed&nbsp;Code</th><th>';
   if (!$_SESSION['bedft']) {
     echo "Beds Seeded</th></tr>";
   } else {
     echo '<div class="styled-select">';
     echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
     echo '<option value = "bed" selected>Bed Feet Seeded: </option>';
     echo '<option value = "row">Row Feet Seeded: </option>';
     echo '</select>';
     echo '</div></th></tr></thead>';
   }
   echo '<tbody></tbody></table>';
   echo '<br clear = "all"/>';
   // echo '<br clear = "all"/>';
   echo '<div class="pure-g">';
   echo '<div class="pure-u-1-2">';
   echo '<input type="button" id="addVariety" name="addVariety" class="genericbutton pure-button wide" onClick="addRow();"';
   echo ' value="Add Variety">';
   echo "</div>";
   echo '<div class="pure-u-1-2">';
   // echo '&nbsp;&nbsp';
   echo '<input type="button" id="removeVariety" name="removeVariety" class="genericbutton pure-button wide" ';
   echo 'onClick="removeRow();" value="Remove Variety">';
   echo "</div>";
   echo "</div>";
   echo '<p>';
  //  echo '<br clear = "all"/>';
   // echo '<br clear = "all"/>';
}

echo '<div class="pure-control-group">';
if ($_SESSION['seed_order']) {
  echo '<label>Total number of ';
  if ($_SESSION['bedft']) {
     echo 'Feet';
  } else {
     echo 'Beds';
  }
  echo ' Seeded:</label> ';
} else if ($_SESSION['bedft']) {
  // echo '<div class="styled-select">';
  echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
  echo '<option value = "bed" selected>Bed Feet Seeded: </option>';
  echo '<option value = "row">Row Feet Seeded: </option>';
  echo '</select>&nbsp;';
//  echo '</div>';
} else {
  echo '<label for="bed">';
  echo "Beds Seeded:";
  echo '</label>';
}
?>
<input type="text" onkeypress= 'stopSubmitOnEnter(event);' 
<?php if ($_SESSION['seed_order']) { echo ' readonly '; } ?>
name ="bedftv" id="bedftv" value ="0">
</div>
<div class="pure-control-group">
<label for="rowbd">Rows per bed:</label>
<select name ="rowbd" id="rowbd" class='mobile-select'>
<option value = 1 selected>1</option>
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

<?php
include $_SERVER['DOCUMENT_ROOT'].'/Seeding/getGen.php';
if ($_SESSION['labor']) {
echo '
<div class="pure-control-group">
<label for="numWorkers">Number of workers (optional):</label>
<input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input single_table">
</div>

<div class="pure-control-group">
<label>Enter time in Hours or Minutes:</label>
<input onkeypress=\'stopSubmitOnEnter(event)\'; type="text" name="time" id="time" 
   class="textbox2 mobile-input-half single_table" value="1">
<select name="timeUnit" id="timeUnit" class=\'mobile-select-half single_table\'>
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div> ';
}
?>

<div class="pure-control-group">
<label for="comments">Comments:</label>
<textarea name ="comments"
rows="5" cols="30">
</textarea>
</div>
<br clear = "all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input class="submitbutton pure-button wide" type="button" value="Submit" 
 onclick= "show_confirm();">
<fieldset>
</form>
</div>

<div class="pure-u-1-2">
<form method="GET" action = "plantReport.php">
<input type="hidden" name="tab" value="seeding:direct:direct_report">
<input type="submit" class="submitbutton pure-button wide" value = "View Table"></form>
</div>
</div>
<?php
//if(isset($_POST['submit'])) {
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $bedftv = escapehtml($_POST['bedftv']);
   $numrows = escapehtml($_POST['rowbd']);
   $crop = escapehtml($_POST['cropButton']);
   if ($_SESSION['bedft'] && $_POST['rowBed'] == "row") {
      $bedftv = $bedftv / $numrows;
   }
   $fld = escapehtml($_POST['fieldID']);

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

   if (!$_SESSION['bedft']) {
      $sql = "select length from field_GH where fieldID = '".$fld."'";
      $result = mysql_query($sql); 
      $row= mysql_fetch_array($result);
      $len = $row['length'];
      $bedftv = $bedftv * $len;
   } 

   $comSanitized=escapehtml($_POST['comments']);
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';

   if ($_SESSION['seed_order']) {
      $sql = "select seedsGram, seedsRowFt from seedInfo where crop = '".$crop."'";
      $res = mysql_query($sql);
      $seedInfo = true;
      if ($row = mysql_fetch_array($res)) {
         $seedsGram = $row['seedsGram'];
         $seedsRowFt = $row['seedsRowFt'];
      } else {
          // echo "<script>alert(\"No seeding information found!\");</script>\n";
         $seedInfo = false;
      }
      $numRows = $_POST['numRows'];
      for ($i = 1; $i <= $numRows; $i++) {
         if (isset($_POST['code'.$i])) {
            $code = escapehtml($_POST['code'.$i]);
            $bf = $_POST['bedftv'.$i];
            $bd = " beds";
            if ($_SESSION['bedft']) {
               if ($_POST['rowBed'] == "row") {
                  $bf = $bf / $numrows;
               }
               $bd = " bed feet";
            }
            if ($comSanitized != "") {
               $comSanitized .= "<br>";
            }
            $var = "select variety from seedInventory where code ='".$code."' and crop = '".$crop."'";
            $vr = mysql_query($var);
            echo mysql_error();
            if ($vrow = mysql_fetch_array($vr)) {
               $variety = $vrow['variety'];
            } else {
               $variety = "No Variety";
            }
            $comSanitized .= "Seed Code: ".escapehtml($_POST['code'.$i])." (".$variety.") - ".
               number_format((float) $bf, 1, '.', '').$bd;
            if (!$_SESSION['bedft']) {
               $bf = $bf * $len;
            }
            if ($seedInfo && $code != "N/A") {
               $seedsPlanted = $seedsRowFt * $numrows * $bf;
               $grams = $seedsPlanted / $seedsGram;
               $dec = "update seedInventory set inInventory = inInventory - ".$grams." where crop = '".
                  $crop."' and code = '".$code."'";
               $decres = mysql_query($dec);
               echo mysql_error();
            }
         }
     }
   }

$sql="INSERT INTO dir_planted(username,fieldID,crop,plantdate,bedft,rowsBed,hours,comments, gen)
   VALUES
   ('".$_SESSION['username']."','".$fld."','".$crop."','".$_POST['year']."-".$_POST['month']."-".
      $_POST['day']."',".$bedftv.", ".$numrows.", ".$totalHours.", '".$comSanitized."', ".$gen.")";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>showError(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }   
} 
?>

