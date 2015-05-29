<?php session_start(); ?>
<?php
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' class='pure-form pure-form-aligned' method='GET' action='report.php?tab=soil:soil_spray:tspray:tspray_report'>
<input type="hidden" name="tab" value="soil:soil_spray:tspray:tspray_report">
<center>
<h2 class="hi"> Tractor Spray Report </h2>
</center>

<div class="pure-control-group">
<label for='from'>From:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<div class="pure-control-group">
<label for='to'>To:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>

<div class="pure-control-group">
<label for='from'>Material:</label>
<select id="material" name="material" class="mobile-select"> <option value=%>All</option>
<?php
$sqlM="SELECT sprayMaterial FROM tSprayMaterials";
$resultM=mysql_query($sqlM);
//echo mysql_error();
while($rowM=mysql_fetch_array($resultM)){
echo "<option value=\"".$rowM['sprayMaterial']."\">".$rowM['sprayMaterial']."</option>\n";
}
echo "</select>";
echo "</div>";
?>

<div class="pure-control-group">
<label>Crop:</label>
<select id="crop" name="crop" class="mobile-select"> <option value=%>All</option>
<?php
$sqlM="SELECT crop FROM plant";
$resultM=mysql_query($sqlM);
//echo mysql_error();
while($rowM=mysql_fetch_array($resultM)){
echo "<option value=\"".$rowM['crop']."\">".$rowM['crop']."</option>\n";
}
echo "</select>";
echo "</div>";
?>

<div class="pure-control-group">
<label for='inst'>Show Spray Queue:</label>
<select id="inst" name="inst" class="mobile-select"> 
<option value=0 selected>No</option>
<option value=1>Yes</option>
</select></div>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton pure-button wide" value="Submit" type="submit" name="submit" >
</body>
</html>
