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
   renamediv.innerHTML = '<div class="pure-control-group" id="renamediv">' +
     '<label for="rename">Rename Plant:</label> ' +
     '<input onkeypress="stopSubmitOnEnter(event)"; type="text" name="rename" id="rename" value="' + 
     escapeHtml(crp)+'"></div>';

   var dhdiv = document.getElementById("dhdiv");
   if (dhdiv != null) {
   var dhunit = decodeURIComponent(plantarray[2]);
   dhdiv.innerHTML = ' <div class="pure-control-group" id="dhdiv"> ' +
      "<label for='dh_units'>Change Invoice Units: </label> " +
      "<select name='dh_units' id='dh_units' >" + 

/*
      "</select></div>";
   dhdiv.innerHTML="<div id='dhdiv' class='styled-select'><select name='dh_units' id='dh_units' class='mobile-select'>" + 
*/
   '<option value="'+dhunit+'">' + dhunit + '</option>' +
'<?php
    $sql = "select distinct unit from extUnits";
    $result = $dbcon->query($sql);

        while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
                echo "<option value= \"".$row1['unit']."\">".$row1['unit']."</option>";
        }
?>' + "</select></div>";

   var ucdiv = document.getElementById("ucdiv");
   ucdiv.innerHTML = ' <div class="pure-control-group" id="ucdiv"> ' +
      "<label for='units_per_case'>Change Units Per Case: </label> " +
      "<input onkeypress='stopSubmitOnEnter(event)'; type='text' name='units_per_case' id='units_per_case' value='" +
      plantarray[1] + "'></div>";
   }

   var adiv = document.getElementById("activediv");
   var active = plantarray[3];
   var newActive = '<div class="pure-control-group" id="activediv"> ' +
      '<label for="admin">Change Active Status:</label> ' +
      '<select name="active" id="active">';

/*
"<div class=\"styled-select\" id=\"activediv\"> <select name=\"active\" id=\"active\" class='mobile-select'>";
console.log(active);
*/
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
<h2> Edit/Delete Crop</h2>
</center>
<form name='form' class="pure-form pure-form-aligned" method='POST' action='<?php $_SERVER['PHP_SELF']?>'>

<div class="pure-control-group">
<label for="crop">Crop:</label>
<select name='crop' id='crop' class='mobile-select' onchange='populate();'>
<?php
$result = $dbcon->query("SELECT crop from plant");
        while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
                echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
        }
        echo "</select></div>";
?>

<div class="pure-control-group" id="renamediv">
<label for="rename">Rename Plant:</label>
<input onkeypress='stopSubmitOnEnter(event)'; type="text" name="rename" id="rename" class="textbox25 mobile-input">
</div>

<?php
if ($_SESSION['sales_invoice']) {
   echo ' <div class="pure-control-group" id="dhdiv"> ';
   echo "<label for='dh_units'>Change Invoice Units: </label> ";
   echo "<select name='dh_units' id='dh_units' class='mobile-select'> </select></div>";

//   echo "<input onkeypress='stopSubmitOnEnter(event)'; type='text' name='dh_units' id='dh_units' class='textbox25 mobile-input'>";


   echo ' <div class="pure-control-group" id="ucdiv"> ';
   echo "<label for='units_per_case'>Change Units Per Case: </label> ";
   echo "<input onkeypress='stopSubmitOnEnter(event)'; type='text' name='units_per_case' id='units_per_case'></div>";
}
?>

<div class="pure-control-group" id="activediv"> 
<label for="admin">Change Active Status:</label>
<select name="active" id="active" class='mobile-select'>
</select>
</div>


 
<br clear="all"/>
<input class="submitbutton pure-button wide" name="submit" type="submit" id="submit" value="Submit">
<br clear="all"/>
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
   } else {
      $query = "UPDATE plant SET crop=upper('".$rename."'), active = ".$active." WHERE crop='".$crop."'";
   }
   try {
      $stmt = $dbcon->prepare($query);
      $stmt->execute();
   } catch (PDOException $p) {
      echo "<script>alert(\"Could not edit plant".$p->getMessage()."\");</script>";
      die();
   }
   echo '<script> alert("Changed plant successfully"); </script>';
}

?>
</form>
</body>
</html>
