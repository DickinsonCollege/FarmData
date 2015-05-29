<?php session_start();
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$ids = array("notes", "labor", "seed_order", "harvlist", "soil", "fertility", "cover", "compost",
   "fertilizer", "liquidfertilizer", "dryfertilizer", "tillage", "spraying", "backspray", "tractorspray",
  "scouting", "insect", "weed", "disease", "irrigation", "pump", "sales", "sales_packing", "sales_invoice",
  "bedft", "gens");
$tabsize = array("num_top"=>7, "num_harvest"=>3, "num_soil"=>4, "num_fertility"=>4, "num_fertilizer"=>2,
 "num_spray"=>2, "num_scout"=>3, "num_admin"=>6, "num_add"=>6, "num_add_crop"=>3, "num_add_equip"=>4,
 "num_add_soil"=>5, "num_add_species"=>3, "num_add_other"=>3, "num_edit"=>5,
 "num_edit_soil"=>5, "num_edit_soil_fertility"=>4, "num_edit_soil_material"=>3,
 "num_edit_other"=>5, "num_view_graphs"=>4, "num_sales"=>4, "num_add_sales"=>4, "num_edit_sales"=>3);
//print_r($tabsize);
$sql = "select * from config";
$res = mysql_query($sql);
echo mysql_error();
$vals = array();
$row = mysql_fetch_array($res);
for ($i = 0; $i < count($ids); $i++) {
   $vals[$ids[$i]] = $row[$ids[$i]];
}
$farm = $row['farmname'];
$farmemail = $row['farmemail'];
$sig = $row['sig'];

//echo "<br><br>";
// print_r($vals);
?>
<link type="text/css" href="config.css" rel="stylesheet">
<form name="form" class="pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center><h2>Configure FARMDATA</h2></center>
In the list below, check each FARMDATA component that you wish to include:
<ul>
<li> <input type="checkbox" name="notes" id="notes"> <b>Comments</b> (entry and reporting of comments or
 observations by any user) </input>
<li> <input type="checkbox" name="labor" id="labor"> <b>Labor</b>
  (tracking labor hours for direct seeding, transplanting, harvesting and any other tasks)</input>
<li> <input type="checkbox" name="seed_order" id="seed_order"> <b>Seed Ordering and Inventory</b>
  (tracking seed orders and inventory, including seed usage during planting)</input>
<li> <input type="checkbox" name="harvlist" id="harvlist"> <b>Harvest List</b> (entering and editing a harvest
 plan for a specific day plus real time harvest tracking)</input>
<li> <input type="checkbox" name="gens" id="gens"> <b>Succession Numbers</b> (distinguishing 
 generations/successions of a crop over multiple seedings in the same field)</input>
<li> <input type="checkbox" name="soil" class="soil" id="soil" 
   onclick="toggle('soil',document.getElementById('soil').checked);"> <b>Soil</b></input>
<ul>
<li>
<li> <input type="checkbox" name="fertility" class="soil fertility" id="fertility"
   onclick="toggle('fertility',document.getElementById('fertility').checked);
  selectParens(['soil'], document.getElementById('fertility').checked);"> <b>Fertility</b></input>
  <ul>
  <li> <input type="checkbox" name="cover" class="soil fertility cover" 
   onclick="selectParens(['soil', 'fertility'], document.getElementById('cover').checked);" id="cover">
      <b>Cover Crop</b> (cover crop planting and incorporation)</input>
  <li> <input type="checkbox" name="compost" class="soil fertility compost" 
   onclick="selectParens(['soil', 'fertility'], document.getElementById('compost').checked);" id="compost">
      <b>Compost</b> (compost pile accumulation and maintenance, compost application)</input>
  <li> <input type="checkbox" name="fertilizer" class="soil fertility fertilizer" 
   onclick="toggle('fertilizer',document.getElementById('fertilizer').checked);
   selectParens(['soil', 'fertility'], document.getElementById('fertilizer').checked);" 
   id="fertilizer">
      <b>Fertilizer</b></input>
  <ul>
  <li> <input type="checkbox" name="liquidfertilizer" class="soil fertility fertilizer liquidfertilizer" 
   onclick="selectParens(['soil', 'fertility', 'fertilizer'], document.getElementById('liquidfertilizer').checked);" 
   id="liquidfertilizer">
      <b>Liquid Fertilizer</b> (application via irrigation)</input>
  <li> <input type="checkbox" name="dryfertilizer" class="soil fertility fertilizer dryfertilizer" 
   onclick="selectParens(['soil', 'fertility', 'fertilizer'], document.getElementById('dryfertilizer').checked);" 
   id="dryfertilizer">
      <b>Dry Fertilizer</b> (application)</input>
  </ul>
  <li> <input type="checkbox" name="tillage" class="soil fertility tillage" 
   onclick="selectParens(['soil', 'fertility'], document.getElementById('tillage').checked);" 
   id="tillage">
      <b>Tillage</b> (tracking)</input>
  </ul>
<li> <input type="checkbox" name="spraying" class="soil spraying" 
   onclick="toggle('spraying',document.getElementById('spraying').checked);
  selectParens(['soil'], document.getElementById('spraying').checked);"
  id="spraying"> <b>Spraying</b></input>
  <ul>
  <li> <input type="checkbox" name="backspray" class="soil spraying backspray" 
   onclick="selectParens(['soil', 'spraying'], document.getElementById('backspray').checked);" 
   id="backspray">
      <b>Backpack Spraying</b> (tracking)</input>
  <li> <input type="checkbox" name="tractorspray" class="soil spraying tractorspray" 
   onclick="selectParens(['soil', 'spraying'], document.getElementById('tractorspray').checked);" 
   id="tractorspray">
      <b>Tractor Spraying</b> (tracking)</input>
  </ul>
<li> <input type="checkbox" name="scouting" class="soil scouting" 
   onclick="toggle('scouting',document.getElementById('scouting').checked);
  selectParens(['soil'], document.getElementById('scouting').checked);"
  id="scouting"> <b>Scouting</b></input>
  <ul>
  <li> <input type="checkbox" name="insect" class="soil scouting insect" 
   onclick="selectParens(['soil', 'scouting'], document.getElementById('insect').checked);" 
   id="insect">
      <b>Insect</b> (tracking levels of insect infestation)</input>
  <li> <input type="checkbox" name="weed" class="soil scouting weed" 
   onclick="selectParens(['soil', 'scouting'], document.getElementById('weed').checked);" 
   id="weed">
      <b>Weed</b> (tracking levels of weed infestation)</input>
  <li> <input type="checkbox" name="disease" class="soil scouting disease" 
   onclick="selectParens(['soil', 'scouting'], document.getElementById('disease').checked);" 
   id="disease">
      <b>Disease</b> (tracking levels of disease infestation)</input>
  </ul>
<li> <input type="checkbox" name="irrigation" class="soil irrigation" 
   onclick="toggle('irrigation',document.getElementById('irrigation').checked);
  selectParens(['soil'], document.getElementById('irrigation').checked);"
  id="irrigation"> <b>Irrigation</b> (tracking irrigation time per field plus rainfall)</input>
   <ul>
   <li> <input type="checkbox" name="pump" class="soil irrigation pump" 
     onclick="toggle('pump',document.getElementById('pump').checked);
    selectParens(['soil', 'irrigation'], document.getElementById('pump').checked);"
    id="pump"> <b>Pump Log</b> (tracking pump runtime and electricity usage - electric motor only)</input>
   </ul>
</ul>
<li> <input type="checkbox" name="sales" class="sales" id="sales"
   onclick="toggle('sales',document.getElementById('sales').checked);"> <b>Sales</b></input>
  <ul>
  <li> <input type="checkbox" name="sales_packing" class="sales sales_packing" id="sales_packing"
   onclick="selectParens(['sales'], document.getElementById('sales_packing').checked);">
   <b>Packing</b> (tracking packing, distribution and inventory of crops and products)</input>
  <li> <input type="checkbox" name="sales_invoice" class="sales sales_invoice" id="sales_invoice"
   onclick="selectParens(['sales'], document.getElementById('sales_invoice').checked);">
    <b>Invoicing</b> (creating, editing and emailing invoices)</input>
  </ul>
</ul>
<b>Seed by the:</b>
<ul>
<li> <input type="radio" name="bedftv" value="bedftv" id="bedftv"> <b>Bed Foot</b></input> (use if different crops
 can be planted in the same bed)
<li> <input type="radio" name="bedftv" value="row" id="bed"> <b>Bed</b></input> (use if only one crop can be
 planted per bed)
</ul>
<h3>Invoice Information (leave blank if not using invoices): </h3>
<div class="pure-control-group">
 <label>Farm Name (as it should appear on invoices): </label> 
    <input type="text" name="FarmName" class="textbox3 mobile-input"
    value="<?php echo $farm;?>">
    </input> 
</div>
<div class="pure-control-group">
 <label>Farm Email (return email address for invoices): </label> 
  <input type="text" name="FarmEmail" class="textbox3 mobile-input"
    value="<?php echo $farmemail;?>">
    </input> 
</div>
<div class="pure-control-group">
<label>Farm Signature (signature on email invoices):</label>
   <textarea name="sig"><?php echo $sig;?></textarea>
</div>


<script type="text/javascript">
var idvs = eval(<?php echo json_encode($vals); ?>);
for (id in idvs) {
  var cid = document.getElementById(id);
  if (cid == null) {
     console.log(id);
  } else {
     cid.checked = (idvs[id] > 0);
  }
 // console.log(id + " " + document.getElementById(id).checked);
  if (idvs['bedft'] == 1) {
    document.getElementById('bedftv').checked = true;
  } else {
    document.getElementById('bed').checked = true;
  }
}

function toggle(tclass, checked) {
   var elements = document.getElementsByClassName(tclass);
   for (i = 0; i < elements.length; i++) {
      var elem = elements[i];
      elem.checked = checked;
   }
}

function selectParens(parens, checked) {
  if (checked) {
    for (par in parens) {
        document.getElementById(parens[par]).checked = true;
    }
  }
}

function show_confirm() {
   return confirm("Confirm FARMDATA Reconfiguration");
}
</script>
<br clear="all"/>
<br clear = "all"/>

<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Submit">
<br clear = "all">
<?php
if (!empty($_POST['done'])) {
   for ($i = 0; $i < count($ids); $i++) {
      $idv = $ids[$i];
      if (isset($_POST[$idv])) {
         $_SESSION[$idv] = 1;
      } else {
         $_SESSION[$idv] = 0;
      }
   }
   if ($_POST['bedftv'] == "bedftv") {
      $_SESSION['bedft'] = 1;
   } else {
      $_SESSION['bedft'] = 0;
   }
   if (!$_SESSION['liquidfertilizer'] && !$_SESSION['dryfertilizer']) {
      $_SESSION['fertilizer'] = 0;
   }
   if ($_SESSION['cover']) {
      $_SESSION['tillage'] = 1;
   }
   if (!$_SESSION['cover'] && !$_SESSION['compost'] && !$_SESSION['fertilizer'] && !$_SESSION['tillage']) {
      $_SESSION['fertility'] = 0;
   }
   if (!$_SESSION['backspray'] && !$_SESSION['tractorspray']) {
      $_SESSION['spraying'] = 0;
   }
   if (!$_SESSION['weed'] && !$_SESSION['insect'] && !$_SESSION['disease']) {
      $_SESSION['scouting'] = 0;
   }
   if (!$_SESSION['fertility'] && !$_SESSION['spraying'] && !$_SESSION['scouting'] && !$_SESSION['irrigation']) {
      $_SESSION['soil'] = 0;
   }
   if (!$_SESSION['sales_packing'] && !$_SESSION['sales_invoice']) {
      $_SESSION['sales'] = 0;
   }

   /* calculate size of each menubar */
   if (!$_SESSION['notes']) {
      $tabsize['num_top']--;
   }
   if (!$_SESSION['labor']) {
      $tabsize['num_top']--;
      $tabsize['num_add_other']--;
      $tabsize['num_edit_other']--;
   }
   if (!$_SESSION['harvlist']) {
      $tabsize['num_harvest']--;
      $tabsize['num_add']--;
   }
   if (!$_SESSION['soil']) {
      $tabsize['num_top']--;
      $tabsize['num_add']--;
   }
   if (!$_SESSION['fertility']) {
      $tabsize['num_soil']--;
      $tabsize['num_edit_soil']--;
   }
   if (!$_SESSION['cover']) {
      $tabsize['num_fertility']--;
      $tabsize['num_add_soil']--;
      $tabsize['num_edit_other']--;
      $tabsize['num_edit_soil_fertility']--;
   }
   if (!$_SESSION['compost']) {
      $tabsize['num_fertility']--;
      $tabsize['num_add_soil']--;
      $tabsize['num_edit_soil']--;
      $tabsize['num_edit_soil_fertility']--;
      $tabsize['num_edit_other']--;
   }
   if (!$_SESSION['fertilizer']) {
      $tabsize['num_fertility']--;
      $tabsize['num_add_soil']--;
      $tabsize['num_edit_soil_fertility']--;
   }
   if (!$_SESSION['dryfertilizer']) {
      $tabsize['num_fertilizer']--;
      $tabsize['num_edit_soil_material']--;
   }
   if (!$_SESSION['liquidfertilizer']) {
      $tabsize['num_fertilizer']--;
      $tabsize['num_edit_soil_material']--;
   }
   if (!$_SESSION['tillage']) {
      $tabsize['num_fertility']--;
      $tabsize['num_add_equip']--;
      $tabsize['num_add_equip']--;
      $tabsize['num_edit_soil_fertility']--;
   }
   if (!$_SESSION['spraying']) {
      $tabsize['num_edit_soil_material']--;
      $tabsize['num_soil']--;
      $tabsize['num_add_soil']--;
      $tabsize['num_edit_soil']--;
      if (!$_SESSION['fertilizer']) {
         $tabsize['num_edit_soil']--;
      }
   }
   if ($tabsize['num_edit_soil_material'] == 0) {
      $tabsize['num_edit']--;
   }
   if (!$_SESSION['backspray']) {
      $tabsize['num_spray']--;
   }
   if (!$_SESSION['tractorspray']) {
      $tabsize['num_spray']--;
   }
   if (!$_SESSION['scouting']) {
      $tabsize['num_soil']--;
      $tabsize['num_add_soil']--;
      $tabsize['num_edit_soil']--;
   }
   if (!$_SESSION['insect']) {
      $tabsize['num_scout']--;
   }
   if (!$_SESSION['weed']) {
      $tabsize['num_scout']--;
   }
   if (!$_SESSION['disease']) {
      $tabsize['num_scout']--;
   }
   if (!$_SESSION['irrigation']) {
      $tabsize['num_soil']--;
      $tabsize['num_add_equip']--;
   }
   if (!$_SESSION['sales']) {
      $tabsize['num_admin']--;
      $tabsize['num_add']--;
      $tabsize['num_edit']--;
   }
   if (!$_SESSION['sales_invoice']) {
      $tabsize['num_sales']--;
      $tabsize['num_view_graphs']--;
      $tabsize['num_edit_sales']--;
      $tabsize['num_edit_sales']--;
      $tabsize['num_edit_sales']--;
   }
   if (!$_SESSION['sales_packing']) {
      $tabsize['num_sales']--;
      $tabsize['num_sales']--;
      $tabsize['num_sales']--;
   }
  
   $sqlUpdate = "update config set ";
   for ($i = 0; $i < count($ids); $i++) {
      $sqlUpdate .= $ids[$i]." = ".$_SESSION[$ids[$i]].", ";
   }
   $sqlUpdate .= "farmname='".escapehtml($_POST['FarmName'])."', farmemail='".
      escapehtml($_POST['FarmEmail'])."', sig='".escapehtml($_POST['sig'])."'";
   foreach ($tabsize as $tname=>$tval) {
      $sqlUpdate .= ", ".$tname." = ".$tval;
   }
 
// echo $sqlUpdate;
   mysql_query($sqlUpdate);
   echo mysql_error();

/*
   for ($i = 0; $i < count($ids); $i++) {
      $idv = $ids[$i];
 echo $idv;
         echo $_SESSION[$idv];
echo "<br>";
   }
*/
   echo "<script>alert(\"FARMDATA configuration updated!\");</script>\n";
   if ($farm == 'dfarm') {
      echo "<meta http-equiv='refresh' content=0;URL='/home.php'>";
   } else {
      echo "<meta http-equiv='refresh' content=0;URL='/exthome.php'>";
   }
}
?>

