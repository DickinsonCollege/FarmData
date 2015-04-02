<?php session_start();?>
<!DOCTYPE html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<form name='form' id='transform' method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>?tab=seeding:transplant:transplant_input">
<h3 class="hi"> Transplant Data Input Form </h3>
<br clear="all"/>
<label for="transferred"> Date of Transplant:&nbsp; </label>
<?php
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
//include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all">
<?php
include 'fieldID_trans.php';
echo "<br clear=\"all\">";
?>
<label for="field">Name of Field:&nbsp;</label>
<div class="styled-select">
<select name="fieldID" id="fieldID" class='mobile-select'>
<option selected="selected" disabled="disabled" value = 0>Field Name</option>
<?php
$result = mysql_query("SELECT fieldID from field_GH where active = 1");
while ($row1 =  mysql_fetch_array($result)){
  echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<?php
$farm = $_SESSION['db'];
if (!$_SESSION['bedft']) {
   echo '<label for="bed">Beds Planted:&nbsp;</label>';
} else {
  echo '<div class="styled-select">';
  echo '<select name ="rowBed" id="rowBed" class="mobile-select">';
  echo '<option value = "bed" selected>Bed Feet Planted: </option>';
  echo '<option value = "row">Row Feet Planted: </option>';
  echo '</select>';
  echo '</div>';
}
?>
<label> &nbsp;</label>
<input class="textbox2 mobile-input single_table" type="text" name ="bedftv" value= 0 id="bedftv">
<br clear="all"/>
<br clear='all'/>
<label for="rows">Rows per Bed:&nbsp;</label>
<div class="styled-select">
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
<br clear="all"/>
<label for="numFlats">Number of trays:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" name="numFlats" id="numFlats" class="textbox2 mobile-input single_table">

<br clear="all"/>
<?php
if ($_SESSION['gens']) {
   echo '<label for="gen">Succession #:&nbsp; </label>';
   echo '<div id="genDiv" class="styled-select">';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   $sql = "select distinct gen from gh_seeding order by gen";
   $res = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($res)) {
     $i = $row['gen'];
     echo "\n<option value='".$i."'>".$i."</option>";
   }
   echo '</select>';
   echo '</div>';
   echo '<br clear="all">';
}

if ($_SESSION['labor']) {
   echo '
<label for="numWorkers">Number of workers:&nbsp;</label>
<input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input single_table">
<br clear="all"/>

<label>Enter time in Hours or Minutes:</label>
<br clear="all"/>
<input onkeypress=\'stopSubmitOnEnter(event)\'; type="text" name="time" id="time" value="1"
  class="textbox2 mobile-input-half single_table">
<div class="styled-select">
<select name="timeUnit" id="timeUnit" class=\'mobile-select-half single_table\'>
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div>
<br clear="all"/>';
}
?>

<div>
<label for="comments">Comments:</label>
<br clear="all"/>
<textarea name ="comments"
row="10" coms= "30">
</textarea>
</div>
<br clear="all"/>
<input class="submitbutton" type="button" value="Submit"  onclick="show_confirm();">
</form>
<br clear="all"/>
<?php
echo '<form method="POST" action = "/Seeding/transplantReport.php?tab=seeding:transplant:transplant_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
?>


<script>
     function show_confirm() {
        var i = document.getElementById("cropButton");
        var strUser3 = i.value;
         if(checkEmpty(strUser3) || strUser3=="Crop") {
        showError("Please Select A Crop");
        return false;
        }
	var con="Crop: "+ strUser3+ "<br>";
	var i = document.getElementById("seedDate");
        var strUser3 = i.value;
        console.log(strUser3+"seedDate");
	 if(checkEmpty(strUser3)) {
        showError("Please Select A Crop that Has Been Seeded");
        return false;
        }
        var con=con+"Seed Date: "+ strUser3+ "<br>";
        var i = document.getElementById("fieldID");
        var strUser3 = i.value;
         if(checkEmpty(strUser3)) {
        showError("Please Select A Field Name");
        return false;
        }
        var con=con+"Name of Field: "+ strUser3+ "<br>";
        var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Date of Transplant: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"<br>";

        var i = document.getElementById("bedftv").value;

        var r = document.getElementById("rowbd").value;
        var rowBed = document.getElementById("rowBed");

        if(checkEmpty(r)) {
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
        if (checkEmpty(i) || isNaN(i) || i<=0) {
           showError("Please enter valid "+bed+"!");
           return false;
        }
        var con=con+bed+": "+ i + "<br>";
        var con=con+"Rows/Bed: "+ r + "<br>";

        var numF = document.getElementById("numFlats").value;
        if (checkEmpty(numF) || numF<=0 || !isFinite(numF)) {
           showError("Please enter a valid number of trays!");
           return false;
        }
        var con=con+"Number of trays: "+ numF+ "<br>";

<?php
  include $_SERVER['DOCUMENT_ROOT'].'/Seeding/checkGen.php';
  if ($_SESSION['labor']) {
    echo '
        var numW = document.getElementById("numW").value;
        if (checkEmpty(numW) || numW<=0 || !isFinite(numW)) {
           showError("Enter a valid number of workers!");
           return false;
        }
        var con=con+"Number of workers: "+ numW+ "<br>";

        var tme = document.getElementById("time").value;
	var unit = document.getElementById("timeUnit").value;
        if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
           showError("Enter a valid number of " + unit + "!");
           return false;
        }
	con = con+"Number of " + unit + ": " + tme + "<br>";';
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
      $result = mysql_query($sql);
      $row= mysql_fetch_array($result);
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

   $sql="INSERT INTO transferred_to(username,fieldID,crop,seedDate,transdate,bedft,rowsBed,flats,gen,hours,comments) VALUES ('".
      $user."','".$fld."','".$crop."','".$_POST['seedDate']."','".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."',".$bedftv.
      ", ".$numrows.", ".$numFlats.", ".$gen.", ".$totalHours.",'".$comSanitized."')";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>showError(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }   

}   
?>
