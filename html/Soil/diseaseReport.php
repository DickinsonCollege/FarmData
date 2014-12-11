<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Disease Scout Report </h3>
<br clear="all"/>
<form name='form' id='test'  method='POST' action="diseaseTable.php?tab=soil:soil_scout:soil_disease:disease_report">
<label for='date'> From: &nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for='date2'> To:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
?>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/fieldID.php';
?>
<label for="disease"> Disease Species:&nbsp; </label>
 <div class="styled-select">
 <select name ="disease" id="disease" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("Select diseaseName from disease");
 while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[diseaseName]\">$row1[diseaseName]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>
<label for="cropDiv"> Crop:&nbsp; </label>
 <div class="styled-select" id="cropDiv">
 <select name ="crop" id="crop" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("select crop from plant");
 while ($row1 =  mysql_fetch_array($result)){
    echo "\n<option value= '".$row1['crop']."'>".$row1['crop']."</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>

<label for="Stage"> Stage: &nbsp;</label>
 <div class="styled-select">
 <select name ="stage" id="stage" class="mobile-select">
 <option value = "%" selected > All</option>
 <?php
 $result=mysql_query("Select stage from stage");
 while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[stage]\">$row1[stage]</option>";
 }
 echo '</select>';
 echo '</div>';
 ?>
<br clear="all"/>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit">
</body>
</html>
