<?php 
session_start(); 
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$farm = $_SESSION['db'];
?>
<script type="text/javascript">
function populate() {
   var crp = document.getElementById("crop").value;
   var crop = encodeURIComponent(crp);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getPlant.php?crop="+crop, false);
   xmlhttp.send();
   var plantarray = eval(xmlhttp.responseText);
   var renamediv = document.getElementById("renamediv");
   renamediv.innerHTML='<div id ="renamediv"><input onkeypress="stopSubmitOnEnter(event)"; type="text" name="rename" id="rename" class="textbox25 mobile-input" value="'+escapeHtml(crp)+'"></div>';
   var dhdiv = document.getElementById("dhdiv");
   if (dhdiv != null) {
   var dhunit = decodeURIComponent(plantarray[2]);
   dhdiv.innerHTML="<div id='dhdiv' class='styled-select'><select name='dh_units' id='dh_units' class='mobile-select'>" + 
   '<option value="'+dhunit+'">' + dhunit + '</option>' +
'<?php
    $sql = "select distinct unit from extUnits";
    $result = mysql_query($sql);

        while ($row1 =  mysql_fetch_array($result)){
                echo "<option value= \"".$row1['unit']."\">".$row1['unit']."</option>";
        }
?>' + "</select></div>";
   var ucdiv = document.getElementById("ucdiv");
   ucdiv.innerHTML="<div id='ucdiv'> <input onkeypress='stopSubmitOnEnter(event)'; type='text' name='units_per_case' id='units_per_case' class='textbox25 mobile-input' value='" +
  plantarray[1] + "'></div>";
   }

   var adiv = document.getElementById("activediv");
   var active = plantarray[3];
   var newActive = "<div class=\"styled-select\" id=\"activediv\"> <select name=\"active\" id=\"active\" class='mobile-select'>";
console.log(active);
   if (active == "1") {
      newActive += "<option selected value='1'>Yes</option><option value='0'>No</option>";
   }  else {
      newActive += "<option selected value='0'>No</option><option value='1'>Yes</option>";
   }
   newActive += "</select></div>";
   adiv.innerHTML= newActive;
}
</script>

<h3> Edit/Delete Crop</h3>
<br>
<form name='form' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>
<label for="crop"><b>Crop:&nbsp;</b></label>
<div id='crop2' class='styled-select'>
<select name='crop' id='crop' class='mobile-select' onchange='populate();'>
<option disabled selected>Crop</option>
<?php
$result = mysql_query("SELECT crop from plant");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>

<label for="rename">Rename Plant:</label>
<div id="renamediv">
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="rename" id="rename" class="textbox25 mobile-input">
</div>

<?php
if ($_SESSION['sales_invoice']) {
   echo "<br clear='all'>";
   echo "<label for='dh_units'>Change Invoice Units: </label>";

   echo "<div id='dhdiv' class='styled-select'><select name='dh_units' id='dh_units' class='mobile-select'> </select></div>";

//   echo "<input onkeypress='stopSubmitOnEnter(event)'; type='text' name='dh_units' id='dh_units' class='textbox25 mobile-input'>";

   echo "<br clear='all'>";
   echo "<label for='units_per_case'>Change Units Per Case: </label>";
   echo "<div id='ucdiv'> <input onkeypress='stopSubmitOnEnter(event)'; type='text' name='units_per_case' id='units_per_case' class='textbox25 mobile-input'></div>";
}
?>
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
   $rename = escapehtml(strtoupper($_POST['rename']));
   $active = $_POST['active'];
   if ($_SESSION['sales_invoice']) {
      $dh_units = escapehtml($_POST['dh_units']);
      $units_per_case = escapehtml($_POST['units_per_case']);
      $query = "UPDATE plant SET crop='".$rename."', dh_units='".$dh_units."', units_per_case=".
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
}

?>
</form>
</body>
</html>
