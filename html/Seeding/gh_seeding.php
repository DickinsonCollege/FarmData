<?php session_start();?>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<h3 >Flats Seeding Input Form</h3>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<br clear="all"/>
<h4> Please Input Only One Record Per Day for Each Crop.
<?php
if (!$_SESSION['seed_order']) {
   echo ' <br> List all varieties of the seeded crop in the varieties window below. ';
}
?>
</h4>
<br clear="all"/>
<?php
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
}
echo '<label for="Seed">Date:&nbsp;</label>';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
?>
<?php
$laborc = false;
$harvesting = false;
$transplanting = false;
include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<br clear="all"/>
<input type="hidden" name="numRows" id="numRows" value=0>

<script type="text/javascript">
var numRows = 0;

function update_seeds() {
   var tot = 0;
   for (var i = 1; i <= numRows; i++) {
      if (document.getElementById("row" + i) != null &&
          document.getElementById("row" + i).innerHTML != "") {
         tot += parseFloat(document.getElementById("seeds" + i).value);
      }
   }
   document.getElementById("num_seeds").value = tot;
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
      var table = document.getElementById("ghTable");
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
      cell1.innerHTML = "<input onkeypress= 'stopSubmitOnEnter(event);' type = 'text' name='seeds" + 
         numRows + "' id='seeds"+numRows+"' class='textbox mobile-input inside_table' style='width:100%' " 
         + "oninput='update_seeds();' value='0'>";
   }
}

function removeRow() {
   if (numRows > 0) {
      var row = document.getElementById("row" + numRows);
      row.innerHTML = "";
      numRows--;
      update_seeds();
   }
   if (numRows == 0) {
      var cb = document.getElementById("cropButton");
      cb.disabled=false;
   }
   var nr = document.getElementById("numRows");
   nr.value=numRows;
}
</script>

<label for="numFlats">Number of flats:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" name="numFlats" id="numFlats" class="textbox2 mobile-input">
<br clear="all"/>
<label for="flatSize">Flat size:&nbsp;</label>
<div class="styled-select">
<select name ="flatSize" id="flatSize" class="mobile-select">
<?php
$sql = "select cells from flat";
$result = mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)) {
   echo "\n<option value=".$row1['cells'].">".$row1['cells']."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<?php
if ($_SESSION['seed_order']) {
   echo '<br clear = "all"/>';
   echo '<table id="ghTable" name="ghTable">';
   echo '<tr><th>Seed&nbsp;Code</th><th>Seeds Planted</th></tr>';
   echo '</table>';
   echo '<br clear = "all"/>';
   echo '<input type="button" id="addVariety" name="addVariety" class="genericbutton" onClick="addRow();"';
   echo ' value="Add Variety">';
   echo '&nbsp;&nbsp';
   echo '<input type="button" id="removeVariety" name="removeVariety" class="genericbutton" ';
   echo 'onClick="removeRow();" value="Remove Variety">';
   echo '<br clear = "all"/>';
}
echo '<label for="numSeeds">';
if ($_SESSION['seed_order']) {
   echo 'Total ';
}
echo 'Seeds Planted:&nbsp;</label> ';
echo '<input value=0 type="int" onkeypress="stopSubmitOnEnter(event);" name ="num_seeds" ';
if ($_SESSION['seed_order']) {
   echo ' readonly ';
}
echo ' class="textbox2 mobile-input" id="num_seeds" value="0">';
echo '</input>';
if (!$_SESSION['seed_order']) {
   echo '<br clear = "all"/>';
   echo '<div>';
   echo '<label for="vars">Varieties:</label>';
   echo '<br clear="all"/>';
   echo '<textarea  name="vars"rows="10" cols="30">';
   echo '</textarea>';
   echo '</div>';
   echo '</div>';
}
?>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Seeding/getGen.php';
?>
<div>
<label for="comments">Comments:</label>
<br clear="all"/>
<textarea  name="comments"rows="10" cols="30">
</textarea>
</div>
<br clear="all"/>
<script>
function show_confirm() {
   var crp = document.getElementById("cropButton").value;
   if(checkEmpty(crp) || crp == "Crop") {
      alert("Please Select a Crop");
      return false;
   }

   var con="Crop: "+ crp + "\n";
   var mth = document.getElementById("month").value;        
   con += "SeedDate: " + mth + "-";
   var dy = document.getElementById("day").value;
   con += dy + "-";
   var yr = document.getElementById("year").value;
   con += yr + "\n";
   var numF = document.getElementById("numFlats").value;
   if (checkEmpty(numF) || numF<=0 || !isFinite(numF)) {
      alert("Enter a valid number of flats!");
      return false;
   }
   con += "Number of flats: "+ numF + "\n";

   var fs = document.getElementById("flatSize").value;
   if (checkEmpty(fs) && fs != 0) {
      alert("Please select flats size");
      return false;
   }
   con += "Flats Size: " + fs + " cells\n";

   var ns = document.getElementById("num_seeds").value;
   if ((checkEmpty(ns) && ns != 0) || ns<0 || isNaN(ns)) {
        alert("Enter a valid number of seeds planted");
        return false;
   }
   con += "Number of Seeds: "+ ns + "\n";
   <?php
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/checkGen.php';
   ?>
   var ret = confirm("Confirm Entry:"+"\n"+con);       
   if (ret) {
      document.getElementById('cropButton').disabled=false;
      document.getElementById('num_seeds').disabled=false;
   }
   return ret;
 }
</script>
<input class="submitbutton" type="submit" name="submit" value="Submit" id="submit" onclick= "return show_confirm();">
</form>
<?php
echo '<form method="POST" action = "/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
if(isset($_POST['submit'])) {
   $crop = escapehtml($_POST['crop']);
   $comSanitized=escapehtml($_POST['comments']);
   $seedInfo = true;
   if ($_SESSION['seed_order']) {
      $sql = "select seedsGram, seedsRowFt from seedInfo where crop = '".$crop."'";
      $res = mysql_query($sql);
      if ($row = mysql_fetch_array($res)) {
         $seedsGram = $row['seedsGram'];
         $seedsRowFt = $row['seedsRowFt'];
      } else {
         $seedInfo = false;
         //echo "<script>alert(\"No seeding information found!\");</script>\n";
      }
      $varsSanitized="";
      $numRows = $_POST['numRows'];
      for ($i = 1; $i <= $numRows; $i++) {
         if (isset($_POST['code'.$i])) {
             $code = escapehtml($_POST['code'.$i]);
             $sds = $_POST['seeds'.$i];
             if ($varsSanitized != "") {
                $varsSanitized .= "<br>";
             }
             $var = "select variety from seedInventory where code ='".$code."' and crop = '".$crop."'";
             $vr = mysql_query($var);
             echo mysql_error();
             if ($vrow = mysql_fetch_array($vr)) {
                $variety = $vrow['variety'];
             } else {
                $variety = "No Variety";
             }
             $varsSanitized .="Seed Code: ".$code." (".$variety.") - ".$sds." seeds";
             if ($seedInfo && $code != "N/A") {
                $grams = $sds / $seedsGram;
                $dec = "update seedInventory set inInventory = inInventory - ".$grams.
               " where crop = '".$crop."' and code = '".$code."'";
                $decres = mysql_query($dec);
                echo mysql_error();
             }
         }
      }
   } else {
      $varsSanitized=escapehtml($_POST['vars']);
   }
   include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
   $seeds = escapehtml($_POST['num_seeds']);
   $numFlats = escapehtml($_POST['numFlats']);
   $flatSize = escapehtml($_POST['flatSize']);
   $user = escapehtml($_SESSION['username']);
   $sql="INSERT INTO gh_seeding(username,crop,seedDate,numseeds_planted,flats,cellsFlat,varieties,gen, comments) VALUES ('".
      $user."','".$crop."','".$_POST['year']."-".$_POST['month']."-".
      $_POST['day']."',".$seeds.",".$numFlats.", ".$flatSize.", '".
      $varsSanitized."', ".$gen.", '".$comSanitized."')";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\\nIf this is a duplicate entry error, ask your FARMDATA administrator to correct the record.\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>
