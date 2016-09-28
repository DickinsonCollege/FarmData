<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class = "pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2><b>Add New Cover Crop Species</b></h2></center>

<div class = "pure-control-group">
<label for="covercrop">Cover Crop Species Name:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="cover" id="cover">
</div>

<div class = "pure-control-group">
<label for="covercrop">Drill Rate Minimum:</label>
<input class="textbox2 mobile-input"type="text" name="min" onkeypress= 'stopSubmitOnEnter(event)'; id="min">
</div>

<div class = "pure-control-group">
<label for="covercrop">Drill Rate Maximum:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input"type="text" name="max" id="max">
</div>

<div class = "pure-control-group">
<label for="covercrop">Broadcaster Rate Minimum:</label>
<input class="textbox2 mobile-input"type="text" name="bmin" onkeypress= 'stopSubmitOnEnter(event)'; id="bmin">
</div>

<div class = "pure-control-group">
<label for="covercrop">Broadcaster Rate Maximum:</label>
<input class="textbox2 mobile-input"type="text" name="bmax"onkeypress= 'stopSubmitOnEnter(event)'; id="bmax">
</div>

<div class = "pure-control-group">
<label for="legume">Legume:</label>
<input type="checkbox" name="legume" id="legume" /><label for="checkboxFiveInput"></label>
</div>
<br clear="all"/>

<script type="text/javascript">
function show_confirm() {
   var i = document.getElementById("cover").value;
   if (checkEmpty(i)) {
      alert("Please Enter Cover Crop Name");
      return false;
   }
   var con="Cover Crop Species: "+ i+ "\n";
   var i = document.getElementById("min").value;
   if (checkEmpty(i) || i < 0 || !isFinite(i)) {
      alert("Please Enter Valid Drill Rate Minimum");
      return false;
   }
   con=con+"Drill Rate Minimum: "+ i + "\n";
   var i = document.getElementById("max").value;
   if (checkEmpty(i) || i < 0 || !isFinite(i)) {
      alert("Please Enter Valid Drill Rate Maximum");
      return false;
   }
   con=con+"Drill Rate Maximum:  "+ i+ "\n";
   var i = document.getElementById("bmin").value;
   if (checkEmpty(i) || i < 0 || !isFinite(i)) {
      alert("Please Enter Valid Broadcast Rate Minimum");
      return false;
   }
   con=con+"Broadcast Rate Minimum:  "+ i+ "\n";
   var i = document.getElementById("bmax").value;
   if (checkEmpty(i) || i < 0 || !isFinite(i)) {
      alert("Please Enter Valid Broadcast Rate Maximum");
      return false;
   }
   con=con+"Broadcast Rate Maximum:  "+ i+ "\n";
   var i = document.getElementById("legume").checked;
   con=con+"Legume:  "+ i+ "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<br clear = "all">
<br clear = "all">
<?php
 $legume = 0;
if (!empty($_POST['done'])) {
   if (!empty($_POST['legume'])) {
      $legume=1;
   }
   $cover = escapehtml(strtoupper($_POST['cover']));
   $min = escapehtml($_POST['min']);
   $max = escapehtml($_POST['max']);
   $bmin = escapehtml($_POST['bmin']);
   $bmax = escapehtml($_POST['bmax']);
   if (!empty($cover) && (float) $min > 0 && !empty($min) &&(float)$max > 0 && !empty($max) &&
     (float)($bmin) > 0 && !empty($bmin) && (float)($bmax) > 0 && !empty($bmax)) {
      $sql="Insert into coverCrop(crop,drillRateMin, drillRateMax, brcstRateMin, brcstRateMax, legume) ".
         "values ('".$cover."','".$min."','".$max."','".$bmin."','".$bmax."','".$legume."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add Cover Crop: Please try again!\\n".$p->getMessage().
            "\");</script>";
         die();
      }
      echo "<script>showAlert(\"Added Cover Crop Successfully!\");</script> \n";
      $sql = "insert into coverVariety values('".$cover."', '".$cover."')";
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         echo "<script>alert(\"Could not add Cover Crop Variety".$p->getMessage()."\");</script>";
         die();
      }
      echo "<script>showAlert('Added Cover Crop successfully!');</script> ";
   } else {
      echo    "<script>alert(\"Enter all data!\\n\");</script> \n";
   }
}
?>

