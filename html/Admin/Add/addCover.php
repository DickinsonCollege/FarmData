<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add New Cover Crop Species</b></h1>
<label for="covercrop">Cover Crop Species Name:&nbsp;</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="cover" id="cover">
<br clear="all"/>
<label for="covercrop">Drill Rate Minimum:&nbsp;</label>
<input class="textbox2 mobile-input"type="text" name="min" onkeypress= 'stopSubmitOnEnter(event)'; id="min">
<br clear="all"/>
<label for="covercrop">Drill Rate Maximum:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2 mobile-input"type="text" name="max" id="max">
<br clear="all"/>
<label for="covercrop">Broadcaster Rate Minimum:&nbsp;</label>
<input class="textbox2 mobile-input"type="text" name="bmin" onkeypress= 'stopSubmitOnEnter(event)'; id="bmin">
<br clear="all"/>
<label for="covercrop">Broadcaster Rate Maximum:&nbsp;</label>
<input class="textbox2 mobile-input"type="text" name="bmax"onkeypress= 'stopSubmitOnEnter(event)'; id="bmax">
<br clear="all"/>
<label for="admin">Legume:&nbsp;</label>
<input style="margin-top: 10px;" type="checkbox" name="legume" id="legume" class="imgClass2 regular-checkbox big-checkbox"  /><label for="checkboxFiveInput"></label>
<br clear="all"/>
<br clear="all"/>
<script type="text/javascript">
function show_confirm() {
   var i = document.getElementById("cover").value;
   var con="Cover Crop Species: "+ i+ "\n";
   var i = document.getElementById("min");
   var strUser3 = i.value;
   con=con+"Drill Rate Minimum: "+ strUser3+ "\n";
   var i = document.getElementById("max").value;
   con=con+"Drill Rate Maximum:  "+ i+ "\n";
   var i = document.getElementById("bmin").value;
   con=con+"Broadcaster Rate Minimum:  "+ i+ "\n";
   var i = document.getElementById("bmax").value;
   con=con+"Broadcaster Rate Maximum:  "+ i+ "\n";
   var i = document.getElementById("legume").checked;
   con=con+"Legume:  "+ i+ "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
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
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add cover crop: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Added Cover Crop Successfully!\");</script> \n";
      }
      $sql = "insert into coverVariety values('".$cover."', '".$cover."')";
      $result = mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not add cover crop variety: Please try again!\\n".
              mysql_error()."\");</script>\n";
      } 
   }else {
      echo    "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

