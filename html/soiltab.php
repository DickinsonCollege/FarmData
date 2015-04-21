<div id="soil" style="display:none;" class="hiddentab">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_soil'].'">';
echo '<ul>';
if ($_SESSION['fertility']) {
echo '<li id="li_soil_fert">  
   <a href="/design.php?tab=soil:soil_fert" id = "soil_fert_a" class="inactivetab">Fertility</a> </li>';
}
if ($_SESSION['spraying']) {
echo '<li id="li_soil_spray">  
   <a href="/design.php?tab=soil:soil_spray" id = "soil_spray_a" class="inactivetab">Spraying</a> </li>';
}
if ($_SESSION['scouting']) {
echo '<li id="li_soil_scout">  
   <a href="/design.php?tab=soil:soil_scout" id = "soil_scout_a" class="inactivetab">Scouting</a> </li>';
}
if ($_SESSION['irrigation']) {
echo '<li id="li_soil_irrigation">
   <a href="/design.php?tab=soil:soil_irrigation" id = "soil_irrigation_a" class="inactivetab">Irrigation</a> </li>';
}
echo '</ul>';
/*
} else {
echo '
<div class="tabs tabs2">
<ul>
<li id="li_soil_spray">  
   <a href="/design.php?tab=soil:soil_spray" id = "soil_spray_a" class="inactivetab">Spraying</a> </li>
<li id="li_soil_scout">  
   <a href="/design.php?tab=soil:soil_scout" id = "soil_scout_a" class="inactivetab">Scouting</a> </li>
</ul>';
}
*/
?>
<?php createBR(); ?>
</div>
</div>

<div id="soil_fert" style="display:none;" class="hiddentab">
<?php echo '<div class="tabs tabs'.$_SESSION['num_fertility'].'">'; ?>
<ul>
<?php
if ($_SESSION['cover']) {
echo '<li id="li_soil_cover">  
   <a href="/design.php?tab=soil:soil_fert:soil_cover" id = "soil_cover_a" class="inactivetab">Cover Crop</a> </li>';
}
if ($_SESSION['compost']) {
echo '<li id="li_soil_compost">  
   <a href="/design.php?tab=soil:soil_fert:soil_compost" id = "soil_compost_a" class="inactivetab">Compost</a> </li>';
}
if ($_SESSION['fertilizer']) {
echo '<li id="li_soil_fertilizer">  
   <a href="/design.php?tab=soil:soil_fert:soil_fertilizer" id = "soil_fertilizer_a" class="inactivetab">Fertilizer</a> </li>';
}
if ($_SESSION['tillage']) {
echo '<li id="li_soil_till">  
   <a href="/design.php?tab=soil:soil_fert:soil_till" id = "soil_till_a" class="inactivetab">Tillage</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="soil_cover" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_soil_coverseed">  
   <a href="/design.php?tab=soil:soil_fert:soil_cover:soil_coverseed" id = "soil_coverseed_a" class="inactivetab">Seeding</a> </li>
<li id="li_soil_coverincorp">  
   <a href="/design.php?tab=soil:soil_fert:soil_cover:soil_coverincorp" id = "soil_coverincorp_a" class="inactivetab">Incorporation</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="soil_coverseed" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_coverseed_input">  
   <a href="/Soil/cover_crop.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_input" id = "coverseed_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_coverseed_report">  
   <a href="/Soil/coverReport.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report" id = "coverseed_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_coverincorp" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_coverincorp_input">  
   <a href="/Soil/incorporation.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_input" id = "coverincorp_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_coverincorp_report">  
   <a href="/Soil/incorpReport.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report" id = "coverincorp_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_compost" style="display:none;">
<div class="tabs tabs5">
<ul>
<li id="li_compost_accumulation">
	<a href="/Soil/compostAccumulation.php?tab=soil:soil_fert:soil_compost:compost_accumulation" id="compost_accumulation_a" class="inactivetab">Accumulation</a></li>
<li id="li_compost_activity">
	<a href="/Soil/compostActivity.php?tab=soil:soil_fert:soil_compost:compost_activity" id="compost_activity_a" class="inactivetab">Activity</a></li>
<li id="li_compost_temperature">
	<a href="/Soil/compostTemperature.php?tab=soil:soil_fert:soil_compost:compost_temperature" id="compost_temperature_a" class="inactivetab">Temperature</a></li>
<li id="li_compost_input">  
   <a href="/Soil/compost.php?tab=soil:soil_fert:soil_compost:compost_input" id = "compost_input_a" class="inactivetab">Application</a> </li>
<li id="li_compost_report">  
   <a href="/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report" id = "compost_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_fertilizer" style="display:none;">
<?php echo '<div class="tabs tabs'.$_SESSION['num_fertilizer'].'">'; ?>
<ul>
<?php
if ($_SESSION['liquidfertilizer']) {
echo '<li id="li_liquid_fertilizer">  
   <a href="/design.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer" id = "liquid_fertilizer_a" class="inactivetab">Liquid Fertilizer</a> </li>';
}
if ($_SESSION['dryfertilizer']) {
echo '<li id="li_dry_fertilizer">  
   <a href="/design.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer" id = "dry_fertilizer_a" class="inactivetab">Dry Fertilizer</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="liquid_fertilizer" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_liquid_fertilizer_input">  
   <a href="/Soil/liquidFert.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_input" id = "liquid_fertilizer_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_liquid_fertilizer_report">  
   <a href="/Soil/liquidFertReport.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report" id = "liquid_fertilizer_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="dry_fertilizer" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_dry_fertilizer_input">  
   <a href="/Soil/dryFert.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_input" id = "dry_fertilizer_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_dry_fertilizer_report">  
   <a href="/Soil/fertReport.php?tab=soil:soil_fert:soil_fertilizer:dry_fertilizer:dry_fertilizer_report" id = "dry_fertilizer_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_till" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_till_input">  
   <a href="/Soil/tillage.php?tab=soil:soil_fert:soil_till:till_input" id = "till_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_fertilizer_report">  
   <a href="/Soil/tillageReport.php?tab=soil:soil_fert:soil_till:till_report" id = "till_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_spray" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_spray'].'">';
echo '<ul>';
if ($_SESSION['backspray']) {
echo '<li id="li_bspray">  
   <a href="/design.php?tab=soil:soil_spray:bspray" id = "bspray_a" class="inactivetab">Backpack Spraying</a> </li>';
}
if ($_SESSION['tractorspray']) {
echo '<li id="li_tspray">  
   <a href="/design.php?tab=soil:soil_spray:tspray" id = "tspray_a" class="inactivetab">Tractor Spraying</a> </li>';
}
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="bspray" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_bspray_input">  
   <a href="/Soil/bspray.php?tab=soil:soil_spray:bspray:bspray_input" id = "bspray_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_bspray_report">  
   <a href="/Soil/sprayReport.php?tab=soil:soil_spray:bspray:bspray_report" id = "bspray_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="tspray" style="display:none;">
<?php
if ($_SESSION['admin']) {
   echo '<div class="tabs tabs3">';
} else {
   echo '<div class="tabs tabs2">';
}
?>
<ul>
<li id="li_tspray_input">  
   <a href="/Soil/Tspray/tSpray.php?tab=soil:soil_spray:tspray:tspray_input" id = "tspray_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_tspray_report">  
   <a href="/Soil/Tspray/reportChooseDate.php?tab=soil:soil_spray:tspray:tspray_report" id = "tspray_report_a" class="inactivetab">Report</a> </li>
<?php
if ($_SESSION['admin']) {
echo '<li id="li_tspray_edit_a">  
   <a href="/Admin/Delete/tsprayReport.php?tab=soil:soil_spray:tspray:tspray_edit" id = "tspray_edit_a" class="inactivetab">Edit/Delete</a> </li>';

}
?>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_scout" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_scout'].'">';
echo '<ul>';
if ($_SESSION['insect']) {
echo '<li id="li_soil_pest">  
   <a href="/design.php?tab=soil:soil_scout:soil_pest" id = "soil_pest_a" class="inactivetab">Insect</a> </li>';
}
if ($_SESSION['weed']) {
echo '<li id="li_soil_weed">  
   <a href="/design.php?tab=soil:soil_scout:soil_weed" id = "soil_weed_a" class="inactivetab">Weed</a> </li>';
}
if ($_SESSION['disease']) {
echo '<li id="li_soil_disease">  
   <a href="/design.php?tab=soil:soil_scout:soil_disease" id = "soil_disease_a" class="inactivetab">Disease</a> </li>';
}
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="soil_pest" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_pest_input">  
   <a href="/Soil/pest.php?tab=soil:soil_scout:soil_pest:pest_input" id = "pest_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_pest_report">  
   <a href="/Soil/pestReport.php?tab=soil:soil_scout:soil_pest:pest_report" id = "pest_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_weed" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_weed_input">  
   <a href="/Soil/weed.php?tab=soil:soil_scout:soil_weed:weed_input" id = "weed_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_weed_report">  
   <a href="/Soil/weedReport.php?tab=soil:soil_scout:soil_weed:weed_report" id = "weed_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_disease" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_disease_input">  
   <a href="/Soil/disease.php?tab=soil:soil_scout:soil_disease:disease_input" id = "disease_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_disease_report">  
   <a href="/Soil/diseaseReport.php?tab=soil:soil_scout:soil_disease:disease_report" id = "disease_report_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="soil_irrigation" style="display:none;">
<?php
if ($_SESSION['pump']) {
   echo '<div class="tabs tabs3">';
} else {
   echo '<div class="tabs tabs2">';
}
?>
<ul>
<li id="li_irrigation_input">
   <a href="/Soil/pumpInfo.php?tab=soil:soil_irrigation:irrigation_input" id = "irrigation_input_a" class="inactivetab">Input Form</a> </li>
<li id="li_irrigation_report">
   <a href="/Soil/irrigation.php?tab=soil:soil_irrigation:irrigation_report" id = "irrigation_report_a" class="inactivetab">Field&nbsp;Report</a> </li>
<?php
if ($_SESSION['pump']) {
   echo '<li id="li_pump_report">
   <a href="/Soil/pump.php?tab=soil:soil_irrigation:pump_report" id = "pump_report_a" class="inactivetab">Pump&nbsp;Report</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

