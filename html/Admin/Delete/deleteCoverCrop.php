<?php 
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<script type="text/javascript">
function populate() {
   var crp = document.getElementById("crop").value;
   var crop = encodeURIComponent(crp);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getCoverCrop.php?crop="+crop, false);
   xmlhttp.send();
   var plantarray = eval(xmlhttp.responseText);
   var renamediv = document.getElementById("renamediv");
   renamediv.innerHTML = '<label for="rename">Rename Cover Crop:</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event)" type="text" name="rename" id="rename" ' +
      'value="'+escapeHtml(crp)+'"></div>';

   var dmin = document.getElementById("drillMinDiv");
   dmin.innerHTML = '<label for="drillMin">Drill Seeding Rate (minimum):</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event)" type="text" name="drillMin" id="drillMin" ' +
      ' value="' + plantarray[0] + '"> &nbsp; (lbs/acre)';

   var dmax = document.getElementById("drillMaxDiv");
   dmax.innerHTML = '<label for="drillMax">Drill Seeding Rate (maximum):</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="drillMax" id="drillMax" ' +
      'value="' + plantarray[1] + '"> &nbsp; (lbs/acre)';

   var bmin = document.getElementById("broadMinDiv");
   bmin.innerHTML = '<label for="BroadMin">Broadcast Seeding Rate (minimum):</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="broadMin" id="broadMin" ' +
      'value="' + plantarray[2] + '"> &nbsp; (lbs/acre)';

   var bmax = document.getElementById("broadMaxDiv");
   bmax.innerHTML = '<label for="BroadMax">Broadcast Seeding Rate (maximum):</label> ' +
      '<input onkeypress="stopSubmitOnEnter(event);" type="text" name="broadMax" id="broadMax" ' +
      ' value="' + plantarray[3] + '"> &nbsp; (lbs/acre)';
 
   var legdiv = document.getElementById("legumediv");
   var leg = plantarray[4];
   var newleg = '<label for="legume">Legume:</label> ' +
      '<select name="legume" id="legume">';
   if (leg == "1") {
      newleg += "<option selected value='1'>Yes</option><option value='0'>No</option>";
   }  else {
      newleg += "<option selected value='0'>No</option><option value='1'>Yes</option>";
   }
   newleg += '</select> </div>';
   legdiv.innerHTML = newleg;

   var adiv = document.getElementById("activediv");
   var active = plantarray[5];
   var newActive = '<label>Change Active Status:</label> <select name="active" id="active">';
   if (active == "1") {
      newActive += "<option selected value='1'>Yes</option><option value='0'>No</option>";
   }  else {
      newActive += "<option selected value='0'>No</option><option value='1'>Yes</option>";
   }
   newActive += "</select></div>";
   adiv.innerHTML= newActive;
}
</script>

<center>
<h2> Edit/Delete Cover Crop </h2>
</center>
<form name='form' class='pure-form pure-form-aligned' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>

<div class="pure-control-group">
<label for="crop">Cover Crop:</label>
<select name='crop' id='crop' class='mobile-select' onchange='populate();'>
<option disabled selected>Cover Crop</option>
<?php
$result = mysql_query("SELECT crop from coverCrop");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
        }
        echo "</select></div>";
?>

<div class="pure-control-group" id="renamediv">
<label for="rename">Rename Cover Crop:</label>
<input onkeypress="stopSubmitOnEnter(event)" type="text" name="rename" id="rename" class="textbox3 mobile-input">
</div>

<div class="pure-control-group" id="drillMinDiv">
<label for="drillMin">Drill Seeding Rate (minimum):</label>
<input onkeypress="stopSubmitOnEnter(event)" type="text" name="drillMin" id="drillMin">
&nbsp; (lbs/acre)
</div>

<div class="pure-control-group" id="drillMaxDiv">
<label for="drillMax">Drill Seeding Rate (maximum):</label>
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="drillMax" id="drillMax" >
&nbsp; (lbs/acre)
</div>

<div class="pure-control-group" id="broadMinDiv">
<label for="BroadMin">Broadcast Seeding Rate (minimum):</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="broadMin" id="broadMin">
&nbsp; (lbs/acre)
</div>

<div class="pure-control-group" id="broadMaxDiv">
<label for="BroadMax">Broadcast Seeding Rate (maximum):</label>
<input onkeypress="stopSubmitOnEnter(event);" type="text" name="broadMax" id="broadMax">
&nbsp; (lbs/acre)
</div>

<div class="pure-control-group" id="legumediv">
<label for="legume">Legume:</label>
<select name="legume" id="legume"> </select> </div>

<div class="pure-control-group" id="activediv">
<label for="admin">Change Active Status:</label>
<select name="active" id="active" class='mobile-select'>
</select>
</div>

<br clear="all"/>
<input class="submitbutton pure-button wide" name="submit" type="submit" id="submit" value="Submit">
<?php
if(!empty($_POST['submit'])) {
   $crop = escapehtml($_POST['crop']);
   $rename = escapehtml($_POST['rename']);
   $active = $_POST['active'];
   $legume = $_POST['legume'];
   $drillMin = $_POST['drillMin'];
   $drillMax = $_POST['drillMax'];
   $broadMin = $_POST['broadMin'];
   $broadMax = $_POST['broadMax'];
   $query = "update coverCrop set crop = upper('".$rename."'), drillRateMin = '".$drillMin.
      "', drillRateMax = '".$drillMax.  "', brcstRateMin = '".$broadMin.  "', brcstRateMax = '".$broadMax.
      "', legume = ".$legume.", active = ".$active." where crop='".$crop."'";
   $res = mysql_query($query);
   echo mysql_error();
   if (!$res) {
      echo '<script> alert("Could not edit cover crop, please try again"); </script>';
   } else {
      echo '<script> alert("Changed cover crop successfully"); </script>';
   }
/*
   if ($_SESSION['sales_invoice']) {
      $dh_units = escapehtml($_POST['dh_units']);
      $units_per_case = escapehtml($_POST['units_per_case']);
      $query = "UPDATE plant SET crop=upper('".$rename."'), dh_units='".$dh_units."', units_per_case=".
       $units_per_case.", active= ".$active." WHERE crop='".$crop."'";
      $sql = mysql_query($query) or die(mysql_error());
      if (!$sql) {
         echo '<script> alert("Could not edit plant, please try again"); </script>';
      } else {
         echo '<script> alert("Changed plant successfully"); </script>';
      }
   } else {
      $query = "UPDATE plant SET crop=upper('".$rename."'), active = ".$active." WHERE crop='".$crop."'";
      $sql = mysql_query($query) or die(mysql_error());        
      if (!$sql) {
         echo '<script> alert("Could not edit plant, please try again"); </script>';
      } else {
         echo '<script> alert("Changed plant successfully"); </script>';
      }

   }
  */
}

?>
</form>
</body>
</html>
