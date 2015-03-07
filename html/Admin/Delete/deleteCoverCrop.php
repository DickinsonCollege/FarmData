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
console.log(plantarray);
   var renamediv = document.getElementById("renamediv");
   renamediv.innerHTML='<div id ="renamediv"><input onkeypress="stopSubmitOnEnter(event)"; type="text" name="rename" id="rename" class="textbox3 mobile-input" value="'+escapeHtml(crp)+'"></div>';

   var dmin = document.getElementById("drillMinDiv");
   dmin.innerHTML='<div id="drillMinDiv"> <input onkeypress="stopSubmitOnEnter(event)"' + 
      'type="text" name="drillMin" id="drillMin" class="textbox25 mobile-input" value="' + plantarray[0] + 
      '"> <label>&nbsp; (lbs/acre)</label></div>';

   var dmax = document.getElementById("drillMaxDiv");
   dmax.innerHTML='<div id="drillMaxDiv"> <input onkeypress="stopSubmitOnEnter(event)"' + 
      'type="text" name="drillMax" id="drillMax" class="textbox25 mobile-input" value="' + plantarray[1] + 
      '"> <label>&nbsp; (lbs/acre)</label></div>';

   var bmin = document.getElementById("broadMinDiv");
   bmin.innerHTML='<div id="broadMinDiv"> <input onkeypress="stopSubmitOnEnter(event)"' + 
      'type="text" name="broadMin" id="broadMin" class="textbox25 mobile-input" value="' + plantarray[2] + 
      '"> <label>&nbsp; (lbs/acre)</label></div>';

   var bmax = document.getElementById("broadMaxDiv");
   bmax.innerHTML='<div id="broadMaxDiv"> <input onkeypress="stopSubmitOnEnter(event)"' + 
      'type="text" name="broadMax" id="broadMax" class="textbox25 mobile-input" value="' + plantarray[3] + 
      '"> <label>&nbsp; (lbs/acre)</label></div>';
 
   var legdiv = document.getElementById("legumediv");
   var leg = plantarray[4];
   var newleg = '<div class="styled-select" id="legumediv">' +
      ' <select name="legume" id="legume" class="mobile-select">';
   if (leg == "1") {
      newleg += "<option selected value='1'>Yes</option><option value='0'>No</option>";
   }  else {
      newleg += "<option selected value='0'>No</option><option value='1'>Yes</option>";
   }
   newleg += '</select> </div>';
   legdiv.innerHTML = newleg;

   var adiv = document.getElementById("activediv");
   var active = plantarray[5];
   var newActive = "<div class=\"styled-select\" id=\"activediv\"> <select name=\"active\" id=\"active\" class='mobile-select'>";
   if (active == "1") {
      newActive += "<option selected value='1'>Yes</option><option value='0'>No</option>";
   }  else {
      newActive += "<option selected value='0'>No</option><option value='1'>Yes</option>";
   }
   newActive += "</select></div>";
   adiv.innerHTML= newActive;
}
</script>

<h3> Edit/Delete Cover Crop </h3>
<br>
<form name='form' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>
<label for="crop"><b>Cover Crop:&nbsp;</b></label>
<div id='crop2' class='styled-select'>
<select name='crop' id='crop' class='mobile-select' onchange='populate();'>
<option disabled selected>Cover Crop</option>
<?php
$result = mysql_query("SELECT crop from coverCrop");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>

<label for="rename">Rename Cover Crop:</label>
<div id="renamediv">
<input onkeypress="stopSubmitOnEnter(event)" type="text" name="rename" id="rename" class="textbox3 mobile-input">
</div>
<br clear="all"/>

<label for="drillMin">Drill Seeding Rate (minimum):</label>
<div id="drillMinDiv">
<input onkeypress="stopSubmitOnEnter(event)" type="text" name="drillMin" id="drillMin" class="textbox25 mobile-input">
<label>&nbsp; (lbs/acre)</label>
</div>
<br clear="all"/>

<label for="drillMax">Drill Seeding Rate (maximum):</label>
<div id="drillMaxDiv">
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="drillMax" id="drillMax" class="textbox25 mobile-input">
<label>&nbsp; (lbs/acre)</label>
</div>
<br clear="all"/>

<label for="BroadMin">Broadcast Seeding Rate (minimum):</label>
<div id="broadMinDiv">
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="broadMin" id="broadMin" class="textbox25 mobile-input">
<label>&nbsp; (lbs/acre)</label>
</div>
<br clear="all"/>

<label for="BroadMax">Broadcast Seeding Rate (maximum):</label>
<div id="broadMaxDiv">
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="broadMax" id="broadMax" class="textbox25 mobile-input">
<label>&nbsp; (lbs/acre)</label>
</div>
<br clear="all"/>

<label for="legume">Legume:&nbsp;</label>
<div class="styled-select" id="legumediv">
<select name="legume" id="legume" class='mobile-select'>
</select>
</div>
<br clear="all"/>

<label for="admin">Change Active Status:&nbsp;</label>
<div class="styled-select" id="activediv">
<select name="active" id="active" class='mobile-select'>
</select>
</div>


 
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" name="submit" type="submit" id="submit" value="Submit">
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
   echo $query = "update coverCrop set crop = upper('".$rename."'), drillRateMin = '".$drillMin.
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
