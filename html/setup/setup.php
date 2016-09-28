<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/utilities.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<link type="text/css" href="/tabs.css" rel = "stylesheet">
<link type="text/css" href="/tableDesign.css" rel = "stylesheet">
<link type="text/css" href="/design.css" rel = "stylesheet">

<form name='form' method='post' action="<?php $_PHP_SELF ?>">
<h3 class="hi"><b>FARMDATA Initial Setup</b></h3>
<br clear="all"/>

<?php
$numsteps = 10;
if (!isset($_POST['step'])) {
   echo "Step 1 of ".$numsteps;
   echo '<input type="hidden" name="step" value=1>';
   echo '<br clear="all"/>';
   echo "<h3>Licensing</h3>";
   echo '<br clear="all"/>';
   echo 'FARMDATA is licensed under the GNU Public License, version 3.0.  This ';
   echo 'means that FARMDATA is freely available (including source code), and ';
   echo 'that no warranty (implied or otherwise) is provided.  Please read the ';
   echo 'full license agreement below.';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo 'Do you agree to the terms of the license agreement?';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="licensey" value="Yes">';
   echo ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
   echo '<input type="submit" class="submitbutton" name="licensen" value="No">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   $file = 'gpl-3.0-standalone.html';
   $myfile = fopen($file, "r") or die("Unable to open file!");
   $fc = fread($myfile,filesize($file));
   fclose($myfile);
   echo '<div style="background-color: white;font-size:16pt;border:2px solid;">';
   echo $fc;
   echo '</div>';
} else if (isset($_POST['licensey'])) {
   echo "Step 2 of ".$numsteps;
   echo '<input type="hidden" name="step" value=2>';
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Fields</h3>";
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="skipfield" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/addFieldInternal.php';
} else  if ($_POST['addfields']) {
   echo '<input type="hidden" name="step" value=3>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/addFieldHandle.php';
   echo '<input type="submit" class="submitbutton" name="cont1" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont1']) || isset($_POST['skipfield'])) {
   echo '<input type="hidden" name="step" value=3>';
   echo "Step 3 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Harvest Units</h3>";
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   $file="units.txt";
   $numeric = array(0);
   $dropdown = array(array());
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skipunits" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editFile.php';
} else if (isset($_POST['addunits'])) {
   echo '<input type="hidden" name="step" value=2>';
   $table="extUnits";
   $cols = "unit";
   $upper = array(1);
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertData.php';
   echo '<input type="submit" class="submitbutton" name="cont2" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont2']) || isset($_POST['skipunits'])) {
   echo "Step 4 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Adding Crops</h3>";
   echo '<br clear="all"/>';
   echo "Would you like to configure invoice units now?";
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   echo '<input type="submit" class="submitbutton" name="invoicey" value="Yes">';
   echo ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;';
   echo '<input type="submit" class="submitbutton" name="invoicen" value="No">';
} else if (isset($_POST['invoicey']) || isset($_POST['invoicen'])) {
   echo "Step 5 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Adding Crops</h3>";
   echo '<br clear="all"/>';
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skipplant" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editPlant.php';
} else if (isset($_POST['addplant'])) {
   echo '<input type="hidden" name="step" value=2>';
   $table="plant";
   $cols = "crop,units,units_per_case,dh_units";
   $upper = array(1,1,0,1);
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertData.php';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="cont3" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont3']) || isset($_POST['skipplant'])) {
   echo '<input type="hidden" name="step" value=2>';
   echo "Step 6 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Harvest Unit Conversions</h3>";
   echo '<br clear="all"/>';
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skipconv" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editUnits.php';
} else if (isset($_POST['addunitsConv'])) {
   echo '<input type="hidden" name="step" value=2>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertUnits.php';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="cont4" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont4']) || isset($_POST['skipconv'])) {
   echo '<input type="hidden" name="step" value=3>';
   echo "Step 7 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Cover Crops</h3>";
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   $file="coverCrop.txt";
   $numeric = array(0, 1, 1, 1, 1, 0);
   $dropdown = array(array(), array(), array(), array(), array(), array("TRUE", "FALSE"));
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skipcover" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editFile.php';
} else if (isset($_POST['addcoverCrop'])) {
   echo '<input type="hidden" name="step" value=2>';
   $table="coverCrop";
   $cols = "crop,drillRateMin,drillRateMax,brcstRateMin,brcstRateMax,legume";
   $upper = array(1,0,0,0,0,0);
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertData.php';
   echo '<br clear="all"/>';
   echo '<input type="submit" class="submitbutton" name="cont5" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont5']) || isset($_POST['skipcover'])) {
   echo '<input type="hidden" name="step" value=3>';
   echo "Step 8 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Tillage Implements</h3>";
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   $file="tools.txt";
   $numeric = array(0, 0);
   $dropdown = array(array(), array("INCORPORATION", "OTHER"));
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skiptools" value="Skip">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editFile.php';
} else if (isset($_POST['addtools'])) {
   echo '<input type="hidden" name="step" value=2>';
   $table="tools";
   $cols = "tool_name, type";
   $upper = array(1,1);
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertData.php';
   echo '<input type="submit" class="submitbutton" name="cont6" value="Continue FARMDATA Setup">';
} else if (isset($_POST['cont6']) || isset($_POST['skiptools'])) {
   echo '<input type="hidden" name="step" value=3>';
   echo "Step 9 of ".$numsteps;
   echo '<br clear="all"/>';
   echo "<h3>Setting Up Spray Materials</h3>";
   echo '<br clear="all"/>';
   echo '<input type="hidden" name="step" value=2>';
   $file="sprayMaterials.txt";
   $numeric = array(0, 0,1,1,1,0,1,1,1,2,0);
   $dropdown = array(array(), array("OUNCE", "POUND","TEASPOON","TABLESPOON","CUP","PINT","QUART","GALLON"),
       array(), array(), array(),
       array("OUNCE", "POUND","TEASPOON","TABLESPOON","CUP","PINT","QUART","GALLON"),
       array(), array(), array(), array(), array());
   echo '<form name="form" method="post" action="'.$_SERVER['PHP_SELF'].'">';
   echo '<input type="submit" class="submitbutton" name="skipspraymats" value="Skip to Final Configuration">';
   echo '<br clear="all"/>';
   echo '<br clear="all"/>';
   include $_SERVER['DOCUMENT_ROOT'].'/setup/editFile.php';
} else if (isset($_POST['addsprayMaterials'])) {
   echo '<input type="hidden" name="step" value=2>';
   $table="tSprayMaterials";
   $cols = "sprayMaterial,TRateUnits,TRateMin,TRateMax,TRateDefault,BRateUnits,BRateMin,BRateMax,BRateDefault,REI_HRS,PPE";
   $upper = array(1,1,0,0,0,1,0,0,0,0,0);
   include $_SERVER['DOCUMENT_ROOT'].'/setup/insertData.php';
   echo '<input type="submit" class="submitbutton" name="cont7" value="Finish FARMDATA Setup">';
} else if (isset($_POST['cont7']) || isset($_POST['skipspraymats'])) {
   // header("Location: ../Admin/Config/config.php?tab=admin:admin_config:config");
    echo '<meta http-equiv="refresh" content="0;URL=../Admin/Config/config.php?tab=admin:admin_config:config">';
} else {
   echo '<h3>ERROR: configuration failed.</h3>';
}
?>
</form>
