<?php session_start();?>
<!DOCTYPE html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<form name='form' method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>?tab=seeding:direct:direct_input">
<h3 class="hi"> Direct Seeding Input Form</h3>
<br clear="all"/>
<label for="planted">Date:&nbsp;</label>
<?php
if ($_SESSION['mobile']) echo "<br clear='all'/>";
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>

<label for="fieldcrop">Field:&nbsp;</label>
<div class="styled-select">
<select name="fieldID" id= "fieldID" class='mobile-select'>
<option disabled selected="selected" value = 0  style="display:none; width: auto;"> Field ID  </option>
<?php
$result=mysql_query("Select fieldID from field_GH where active = 1");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"".$row1['fieldID']."\">".$row1['fieldID']."</option>";
}
?>
</select>
</div>
<input type="hidden" name="numRows" id="numRows" value=0>
<?php 
if (!$_SESSION['mobile']) {
   echo "<label> &nbsp;</label>";
   echo "<br clear='all'>";
}
//include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<div class="styled-select">
<select name="cropButton" id="cropButton" class="mobile-select">
<option disabled selected value="0">Crop</option>
<?php
$sql = "select distinct crop from plant";
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
         tot += parseFloat(document.getElementById("bedft" + i).value);
      }
   }
   document.getElementById("bedft").value = tot;
}

function addRow() {
   var cb = document.getElementById("cropButton");
   if (cb.value=="0") {
      alert("Error: choose crop first!");
   } else {
      cb.disabled=true;
      numRows++;
      var nr = document.getElementById("numRows");
      nr.value = numRows;
      var table = document.getElementById("seedTable");
      var row = table.insertRow(numRows);
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
      cell1.innerHTML = "<input onkeypress= 'stopSubmitOnEnter(event);' type = 'text' name='bedft" + numRows
         + "' id='bedft"+numRows+"' class='textbox mobile-input inside_table' style='width:100%' " +
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

<br clear = "all"/>
<?php
if ($_SESSION['seed_order']) {
   echo '<br clear = "all"/>';
   echo '<table id="seedTable" name="seedTable">';
   echo '<tr><th>Seed&nbsp;Code</th><th>';
   if (!$_SESSION['bedft']) {
     echo "Beds Seeded</th></tr>";
   } else {
     echo '<div class="styled-select">';
     echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
     echo '<option value = "bed" selected>Bed Feet Seeded: </option>';
     echo '<option value = "row">Row Feet Seeded: </option>';
     echo '</select>';
     echo '</div></th></tr>';
   }
   echo '</table>';
   echo '<br clear = "all"/>';
   echo '<input type="button" id="addVariety" name="addVariety" class="genericbutton" onClick="addRow();"';
   echo ' value="Add Variety">';
   echo '&nbsp;&nbsp';
   echo '<input type="button" id="removeVariety" name="removeVariety" class="genericbutton" ';
   echo 'onClick="removeRow();" value="Remove Variety">';
   echo '<br clear = "all"/>';
   echo '<br clear = "all"/>';
}

if ($_SESSION['seed_order']) {
  echo '<label>Total ';
  if ($_SESSION['bedft']) {
     echo 'Feet';
  } else {
     echo 'Beds';
  }
  echo ' Seeded:&nbsp;</label>';
} else if ($_SESSION['bedft']) {
  echo '<div class="styled-select">';
  echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
  echo '<option value = "bed" selected>Bed Feet Seeded: </option>';
  echo '<option value = "row">Row Feet Seeded: </option>';
  echo '</select>';
  echo '</div>';
} else {
  echo '<label for="bed">';
  echo "Beds Seeded:&nbsp;";
  echo '</label>';
}
?>
<input class="textbox2 mobile-input" type="text" onkeypress= 'stopSubmitOnEnter(event);' 
<?php if ($_SESSION['seed_order']) { echo ' readonly '; } ?>
name ="bedft" id="bedft" value ="0">
<br clear = "all"/>
<div class="styled-select">
<label for="rowbd">Rows per bed:&nbsp;</label>
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
<br clear="all"/>
<?php
if ($_SESSION['labor']) {
echo '
<label for="numWorkers">Number of workers (optional):&nbsp;</label>
<input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input">
<br clear="all"/>

<label>Enter time in Hours or Minutes:</label>
<br clear="all"/>
<input onkeypress=\'stopSubmitOnEnter(event)\'; type="text" name="time" id="time" class="textbox2 mobile-input-half">
<div class="styled-select">
<select name="timeUnit" id="timeUnit" class=\'mobile-select-half\'>
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div> 
<br clear = "all"/> ';
}
?>

<div>
<label for="comments">Comments:</label>
<br clear = "all"/>
<textarea name ="comments"
rows="10" cols="30">
</textarea>
</div>
<script type="text/javascript">
function show_confirm() {
   var fld = document.getElementById("fieldID").value;
   if (checkEmpty(fld)) {
      alert("Please Select a FieldID");
      return false;
   }

   var con="FieldID: "+ fld + "\n";
   var crp = document.getElementById("cropButton").value;
   if (checkEmpty(crp) || crp == "Crop") {
      alert("Please Select a Crop");
      return false;
   }
   con += "Crop: "+ crp + "\n";
<?php
if ($_SESSION['seed_order']) {
   echo '
   var count = 1;
   for (var i = 1; i <= numRows; i++) {
      if (document.getElementById("row" + i) != null &&
          document.getElementById("row" + i).innerHTML != "") {
         var code = document.getElementById("code" + i).value;
         if (checkEmpty(code)) {
            alert("Please select a seed code in row: " + count);
            return false;
         }
         count++;
      }
   }';
}
?>

   var mth = document.getElementById("month").value;
   con += mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   var i = document.getElementById("bedft").value;
   var r = document.getElementById("rowbd").value;
   if (checkEmpty(r)) {
      alert("Please Select Rows Per Bed");
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
      alert("Enter valid "+bed+"!");
      return false;
   } 
   var con=con+bed+": "+ Math.round(i/div) + "\n";

   var con=con+"Rows/Bed: "+ r+ "\n";

<?php
  if ($_SESSION['labor']) {
     echo '
        var tme = document.getElementById("time").value;
   var unit = document.getElementById("timeUnit").value;
        if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
           alert("Enter a valid number of " + unit + "!");
           return false;
        }
   con = con+"Number of " + unit + ": " + tme + "\n";';
   } 
?>

    var ret = confirm("Confirm Entry:"+"\n"+con);
    if (ret) {
       document.getElementById('cropButton').disabled=false;
       document.getElementById('bedft').disabled=false;
    }
    return ret;
}
</script>
<br clear = "all"/>
<input class="submitbutton" type="submit" name="submit" value="Submit" onclick= "return show_confirm();">
</form>

<br clear = "all"/>
<form method="GET" action = "plantReport.php">
<input type="submit" class="submitbutton" value = "View Table"></form>
<?php
if(isset($_POST['submit'])) {
   $bedft = escapehtml($_POST['bedft']);
   $numrows = escapehtml($_POST['rowbd']);
   $crop = escapehtml($_POST['cropButton']);
   if ($_SESSION['bedft'] && $_POST['rowBed'] == "row") {
      $bedft = $bedft / $numrows;
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
      $bedft = $bedft * $len;
   } 

   $comSanitized=escapehtml($_POST['comments']);

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
            $bf = $_POST['bedft'.$i];
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

   $sql="INSERT INTO dir_planted(username,fieldID,crop,plantdate,bedft,rowsBed,hours,comments)
   VALUES
   ('".$_SESSION['username']."','".$fld."','".$crop."','".$_POST['year']."-".$_POST['month']."-".
      $_POST['day']."',".$bedft.", ".$numrows.", ".$totalHours.", '".$comSanitized."')";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }   
} 
?>

