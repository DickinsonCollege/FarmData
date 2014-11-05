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
while ($row1 =  mysql_fetch_array($result)){  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<?php 
if (!$_SESSION['mobile']) {
	echo "<label> &nbsp;</label>";
	echo "<br clear='all'>";
}
include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<!--
<div class="styled-select">
<select name="crop" id="crop">
<option disabled selected="selected"  value = 0> Crop </option>
<?php
$result=mysql_query("Select crop from plant");
while ($row1 =  mysql_fetch_array($result)){  echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
-->
<br clear = "all"/>
<?php
$farm = $_SESSION['db'];
if (!$_SESSION['bedft']) {
  echo '<label for="bed"><b>';
  echo "Beds Seeded:&nbsp;</b>";
  echo '</label>';
} else {
  if ($_SESSION['mobile']) {
    echo '<div class="styled-select" style="margin-bottom:-100px; margin-top:50px;">';
  } else {
    echo '<div class="styled-select">';
  }
  echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
  echo '<option value = "bed" selected>Bed Feet Seeded: </option>';
  echo '<option value = "row">Row Feet Seeded: </option>';
  echo '</select>';
  echo '</div>';
}
?>
<label> &nbsp;</label>
<input class="textbox2 mobile-input" type="text" onkeypress= 'stopSubmitOnEnter(event);' name ="bedft" id="bedft">
<br clear = "all"/>
<div class="styled-select">
<label for="rowbd"><b>Rows per bed:&nbsp;</b></label>
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
<label for="numWorkers"><b>Number of workers (optional):&nbsp;</b></label>
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
<label for="comments"><b>Comments:</b></label>
<br clear = "all"/>
<textarea name ="comments"
rows="10" cols="30">
</textarea>
</div>
<script type="text/javascript">
        function show_confirm() {
        var i = document.getElementById("cropButton");
	if(checkEmpty(i.value) || i.value == "Crop") {
           alert("Please Select a Crop");
           return false;
        }

        var strUser3 = i.value;
        var con="Crop: "+ strUser3+ "\n";
	var i = document.getElementById("fieldID");
	if(checkEmpty(i.value)) {
           alert("Please Select a FieldID");
           return false;
        }

	var strUser3 = i.options[i.selectedIndex].text;
	var con=con+"FieldID: "+ strUser3+ "\n";
	var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
	var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
	var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"\n";
	var i = document.getElementById("bedft").value;
	console.log(i);
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
	if (checkEmpty(i) || isNaN(i) || i<=0) {
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

        return confirm("Confirm Entry:"+"\n"+con);
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
   $comSanitized=escapehtml($_POST['comments']);
   $bedft = escapehtml($_POST['bedft']);
   $numrows = escapehtml($_POST['rowbd']);
   if ($_SESSION['bedft'] && $_POST['rowBed'] == "row") {
      $bedft = $bedft / $numrows;
   }
   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);

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
   $sql="INSERT INTO dir_planted(username,fieldID,crop,plantdate,bedft,rowsBed,hours,comments)
   VALUES
   ('".$_SESSION['username']."','".$fld."','".$crop."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."',".$bedft.", ".$numrows.", ".$totalHours.", '".$comSanitized."')";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }   
} 
?>

