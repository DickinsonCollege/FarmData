<?php session_start();?>
<html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$farm = $_SESSION['db'];
?>

<h3 >Flats Seeding Input Form</h3>
<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<br clear="all"/>
<h4> Please Input Only One Record Per Day for Each Crop. <br>
List all varieties of the seeded crop in the varieties window below. </h4>
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
include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<!--
<label for="crop">Crop:&nbsp;</label>
<div class="styled-select">
<select name="crop" id="crop">
<option value =0 selected="selected" style="display:none"> Crop</option>
<?php
$result = mysql_query("Select distinct crop from plant");
while ($row1=mysql_fetch_array($result)){
echo "\n<option value=\"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
-->
<br clear="all"/>
<label for="numFlats"><b>Number of flats:&nbsp;</b></label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" name="numFlats" id="numFlats" class="textbox2 mobile-input">
<br clear="all"/>
<label for="flatSize"><b>Flat size:&nbsp;</b></label>
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
if (!$_SESSION['bigfarm']) {
   echo '<label for="numSeeds">Seeds Planted:&nbsp;</label> ';
   echo '<input value=0 type="int" onkeypress="stopSubmitOnEnter(event);" name ="num_seeds" class="textbox2 mobile-input" id="num_seeds">';
   echo '</input>';
echo "<br clear=\"all\">";
}
?>
<div>
<label for="vars">Varieties:</label>
<br clear="all"/>
<textarea  name="vars"rows="10" cols="30">
</textarea>
</div>
</div>
<div>
<label for="comments">Comments:</label>
<br clear="all"/>
<textarea  name="comments"rows="10" cols="30">
</textarea>
</div>
<br clear="all"/>
<script>
        function show_confirm() {
        var i = document.getElementById("cropButton");
        if(checkEmpty(i.value) || i.value == "Crop") {
        alert("Please Select a Crop");
        return false;
        }

	var strUser3 = i.value;
	var con="Crop: "+ strUser3+ "\n";
	var i = document.getElementById("month");        
	var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"SeedDate: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"\n";
        var numF = document.getElementById("numFlats").value;
        if (checkEmpty(numF) || numF<=0 || !isFinite(numF)) {
           alert("Enter a valid number of flats!");
           return false;
        }
        var con=con+"Number of flats: "+ numF+ "\n";

        var i = document.getElementById("flatSize").value;
        if(checkEmpty(i) && i != 0) {
          alert("Please Select the Flats Size");
          return false;
        }
        var con=con+"Flats Size: "+ i+ " cells\n";

        <?php if (!$_SESSION['bigfarm']) {
           echo 'var i = document.getElementById("num_seeds").value;';
	   echo 'if ((checkEmpty(i) && i != 0) || i<0 || isNaN(i)) {';
	 echo '	  alert("Enter a Valid Number of Seeds Planted!");';
	 echo '	  return false;';
	   echo '}';
	   echo 'var con=con+"Number of Seeds: "+ i+ "\n";';
        } ?>
        return confirm("Confirm Entry:"+"\n"+con);       
 }
</script>
<input class="submitbutton" type="submit" name="submit" value="Submit" id="submit" onclick= "return show_confirm();">
</form>
<?php
echo '<form method="POST" action = "/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
if(isset($_POST['submit'])) {
   $comSanitized=escapehtml($_POST['comments']);
   $varsSanitized=escapehtml($_POST['vars']);
   if ($_SESSION['bigfarm']) {
      $seeds = 0;
   } else {
      $seeds = escapehtml($_POST['num_seeds']);
   }
   $numFlats = escapehtml($_POST['numFlats']);
   $flatSize = escapehtml($_POST['flatSize']);
   $crop = escapehtml($_POST['crop']);
   $user = escapehtml($_SESSION['username']);
   $sql="INSERT INTO gh_seeding(username,crop,seedDate,numseeds_planted,flats,cellsFlat,varieties,comments) VALUES ('".
      $user."','".$crop."','".$_POST['year']."-".$_POST['month']."-".
      $_POST['day']."',".$seeds.",".$numFlats.", ".$flatSize.", '".
      $varsSanitized."','".$comSanitized."')";
   $result = mysql_query($sql);
   if(!$result){ 
       echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\\nIf this is a duplicate entry error, ask your FARMDATA administrator to correct the record.\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>
