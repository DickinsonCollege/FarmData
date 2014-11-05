<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h1> Remove Unit From Crop </h1>
<form name='form' method='POST' action='<?php $_SERVER['PHP_SELF']?>'>
<label for="crop"><b>Crop:&nbsp;</b></label>
<div id='crop2' class='styled-select'>
<select name='crop' id='crop' onchange="addInput2();" class="mobile-select">
<option disabled selected>Crop</option>
<?php
$result = mysql_query("SELECT crop from plant");
        while ($row1 =  mysql_fetch_array($result)){
                echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
        }
        echo "</select></div>";
?>
<br clear="all"/>
<label for="crop"><b>Unit:</b></label>
<div id='unit' class='styled-select'>
<select name='unit' id='unit' class="mobile-select">
<option disabled selected>Unit</option>
</select>
</div>
<script type="text/javascript">
function addInput2(){
   var newdiv = document.getElementById('unit');
   var crop = encodeURIComponent(document.getElementById('crop').value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "hupdate.php?crop="+crop, false);
   xmlhttp.send();
   console.log(xmlhttp.responseText);
   newdiv.innerHTML="<div class='styled-select' id ='unit'>  <select name= 'unit' id= 'unit'>"+xmlhttp.responseText+"</select> </div>";
}
 </script>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" name="submit" type="submit" id="submit" value="Submit">
<?php
if(!empty($_POST['submit'])) {
   $crop = escapehtml($_POST['crop']);
   $unit = escapehtml($_POST['unit']);
   if(!empty($crop) && !empty($unit)) {
      $sql = "select units from plant where crop = '".$crop."'";
      $result = mysql_query($sql);
      echo mysql_error();
      $row = mysql_fetch_array($result);
      if ($row['units'] == $unit) {
          echo '<script> alert("Can not delete default unit!"); </script>';
      } else {
         $sql5 = "delete from units where crop='".$crop."' and unit='".$unit."'";
         $totalResult = mysql_query($sql5);
         echo mysql_error();
         if(!$totalResult) {
             echo '<script> alert("Could not process command, please try again"); </script>';
         } else {
             echo '<script type="text/javascript">showAlert("Removed unit successfully!");</script>';
         }
      }
   } else {
      echo '<script> alert("Please select a crop and unit before pressing submit."); </script>';
   }
}
?>
</form>
</html>
