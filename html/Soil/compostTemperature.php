<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3>Compost Temperature Form</h3>
<form method='post' action="<?php $_PHP_SELF ?>?tab=soil:soil_fert:soil_compost:compost_temperature">
<script>
        function show_confirm() {
         var fields = ["pileID", "averageTemperature", "numReadings"];
         var con = "";

        var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        var con="Date: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"\n";

         for (i = 0; i < fields.length; i++) {
            var a = document.getElementById(fields[i]);
            var val = a.value;
            if (checkEmpty(val)) {
               alert("Please Input " + fields[i]);
               return false;
            }
            con += fields[i] + ": ";
            con += val + "\n";
         }

        return confirm("Confirm Entry:"+"\n"+con);
        }

function addTemperatureToTable() {
   var numReadingsInput = document.getElementById("numReadings");
   numReadingsInput.value++;
   var numReadings = numReadingsInput.value;

   var tbl = document.getElementById("temperatureTable");
   var row = tbl.insertRow(-1);
   var cell = row.insertCell(0);

   var cellHTML = "";
   cellHTML += "<input type='text' name='temperature" + numReadings + "' id='temperature" + 
      numReadings + "' class='textbox25 mobile-input' onchange='calculateAverageTemperature();' value=0>"

   cell.innerHTML = cellHTML;

   calculateAverageTemperature();
}

function removeTemperatureFromTable() {
   var numReadingsInput = document.getElementById("numReadings");
   var numReadings = numReadingsInput.value;

   if (numReadings >= 1) {
      numReadingsInput.value--;

      var tbl = document.getElementById("temperatureTable");
      tbl.deleteRow(numReadings);
   }

   calculateAverageTemperature();
}

function calculateAverageTemperature() {
   var numReadings = document.getElementById("numReadings").value;
   var averageTemperature = document.getElementById("averageTemperature");

   var totalTemp = 0;
   for (var i = 1; i <= numReadings; i++) {
      temp = document.getElementById("temperature" + i);
      totalTemp += parseFloat(temp.value);
   }

   averageTemperature.value = (totalTemp/numReadings).toFixed(2);
}
</script>

<br clear="all"/>
<label for="Seed">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>

<br clear="all"/>
<label for="pileIDlabel">Pile ID: </label>
<div class="styled-select" id="pileIDDiv">
<select name ="pileID" id="pileID" class='mobile-select'>
<option value = 0 selected disabled>Pile ID</option>
<?php
$result=mysql_query("Select pileID from compost_pile where active=1");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[pileID]\">$row1[pileID]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all">
<br clear="all">

<table id="temperatureTable">
<tr>
<th>
Temperature Readings: (&deg;F)
</th>
</tr>
<td>
<input type='text' name='temperature1' id='temperature1' class='textbox25 mobile-input' onchange='calculateAverageTemperature();' value=0>
</td>
</tr>
</table>
<input type='hidden' id='numReadings' name='numReadings' value=1>

<label for='averagelabel'>Average Temperature:&nbsp;</label>
<input readonly type='text' id='averageTemperature' name='averageTemperature' class='textbox25 mobile-input' value=0>
<br clear='all'>
<input type='button' class='genericbutton' id='addTemperature' name='addTemperature' onclick='addTemperatureToTable();' value='Add Temperature Reading'>
<input type='button' class='genericbutton' id='removeTemperature' name='removeTemperature' onclick='removeTemperatureFromTable();' value='Remove Temperature Reading'>
<br clear='all'>
<?php if ($_SESSION['mobile']) echo "<div style='margin-top:100px'></div>"; ?>

<label for="commentslabel">Comments:&nbsp;</label>
<br clear='all'>
<textarea name='comments' rows='20' cols='30'>
</textarea>

<br clear="all"/>
<br clear="all"/>

<input onclick="return show_confirm();" type="submit" class = "submitbutton" name="submit" id="submit" value="Submit">
<br clear="all"/>
</form>
<form method="POST" action = "/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report"><input type="submit" class="submitbutton" value = "View Table"></form>
<?php
if (isset($_POST['submit'])) {
   $pileID = escapehtml($_POST['pileID']);
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $date = $year."-".$month."-".$day;
   $comments = escapehtml($_POST['comments']);
   $averageTemperature = escapehtml($_POST['averageTemperature']);
   $numReadings = $_POST['numReadings'];

   $sql = "INSERT INTO compost_temperature (tmpDate, pileID, temperature, numReadings, comments) 
      VALUES ('".$date."', '".$pileID."', '".$averageTemperature."', '".$numReadings."', '".$comments."')";
   $result = mysql_query($sql);

   if(!$result) { 
      echo "<script> alert(\"Could not enter Compost Temperature Data! Try again.\\n ".mysql_error()."\"); </script>";
   }else {
      echo "<script> showAlert(\"Compost Temperature Record Entered Successfully\"); </script>";
   }
}
?>
