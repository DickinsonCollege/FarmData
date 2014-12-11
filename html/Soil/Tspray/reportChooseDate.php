<?php session_start(); ?>
<?php
//include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='form' method='POST' action='report.php?tab=soil:soil_spray:tspray:tspray_report'>
<h3 class="hi"> Tractor Spray Report </h3>
<br clear="all"/>
<label for='from'>From:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for='to'>To:&nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>

<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<label for='from'>Material:&nbsp;</label>
<div class="styled-select">
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
<br clear="all"/>
<label for='from'>Crop:&nbsp;</label>
<div class="styled-select">
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
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" value="Submit" type="submit" name="submit" >
</body>
</html>
