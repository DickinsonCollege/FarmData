<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add Spray Material</b></h1>
<label for="covercrop">Spray Material Name:&nbsp;</label>
<input class="textbox3" onkeypress= 'stopSubmitOnEnter(event)'; type="text" name="cover" id="cover">
<br clear="all"/>
<label for="tunits"> TRate Units</label>
<div class="styled-select">
<select name="tunits" id="tunits">
<option value = 0 selected disabled> TRateUnits </option>
<?php
$sqlget = "Select distinct TRateUnits from tSprayMaterials";
$result = mysql_query($sqlget);
while($row1 = mysql_fetch_array($result)) {
echo "<option value=".$row1['TRateUnits'].">".$row1['TRateUnits']."</option>";
}
echo "</select>";
echo "</div>";
?>
<br clear="all"/>
<label for="covercrop">Drill Rate Minimum:&nbsp;</label>
<input class="textbox2"type="text" name="min" onkeypress= 'stopSubmitOnEnter(event)'; id="min">
<br clear="all"/>
<label for="covercrop">Drill Rate Maximum:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox2"type="text" name="max" id="max">
<br clear="all"/>
<label for="covercrop">Broadcaster Rate Minimum:&nbsp;</label>
<input class="textbox2"type="text" name="bmin" onkeypress= 'stopSubmitOnEnter(event)'; id="bmin">
<br clear="all"/>
<label for="covercrop">Broadcaster Rate Maximum:&nbsp;</label>
<input class="textbox2"type="text" name="bmax"onkeypress= 'stopSubmitOnEnter(event)'; id="bmax">
<br clear="all"/>
<label for="admin">Legume:&nbsp;</label>
<input style="margin-top: 10px;" type="checkbox"name="legume" id="legume" class="imgClass2 regular-checkbox big-checkbox"  /><label for="checkboxFiveInput"></label>
<br clear="all"/>
<br clear="all"/>
<script>
function show_confirm() {
        var i = document.getElementById("cover").value;
        var con="Cover Crop Species: "+ i+ "\n";
        var i = document.getElementById("min");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Drill Rate Minimum: "+ strUser3+ "\n";
	var i = document.getElementById("max").value;
        var con=con+"Drill Rate Maximum:  "+ i+ "\n";
	var i = document.getElementById("bmin").value;
        var con=con+"Broadcaster Rate Minimum:  "+ i+ "\n";
	var i = document.getElementById("bmax").value;
        var con=con+"Broadcaster Rate Maximum:  "+ i+ "\n";


return confirm("Confirm Entry: " +"\n"+con);

}
</script>
<input onclick= "return show_confirm()";  class="submitbutton" type="submit" name="done" value="Add">
<?php
$admin = 0;
if (!empty($_POST['done'])) {
if (!empty($_POST['legume'])) {
$admin=1;
}
if (!empty($_POST['cover']) && (float)($_POST['min']) > 0 && !empty($_POST['min']) &&(float)($_POST['max']) > 0 && !empty($_POST['max']) && (float)($_POST['bmin']) > 0 && !empty($_POST['bmin']) && (float)($_POST['bmax']) > 0 && !empty($_POST['bmax'])) {
$sql="Insert into coverCrop(crop,drillRateMin, drillRateMax, brcstRateMin, brcstRateMax, legume) values (upper('".$_POST['cover']."'),'".$_POST['min']."','".$_POST['max']."','".$_POST['bmin']."','".$_POST['bmax']."','".$admin."')";
$result=mysql_query($sql);
if (!$result) {
echo "<script>alert(\"Could not add cover crop: Please try again!\\n".mysql_error()."\");</script>\n";


}else {
echo "<script>showAlert(\"Added Cover Crop Successfully!\");</script> \n";
}
}else {
echo    "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";

}
}
?>

