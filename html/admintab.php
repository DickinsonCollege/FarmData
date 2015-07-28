<div id="admin" style="display:none;">
<?php
   echo '<div class="tabs tabs'.$_SESSION['num_admin'].'">';
echo '<ul>';
echo '<li id="li_admin_add">  
   <a id = "admin_add_a" href="/design.php?tab=admin:admin_add" class="inactivetab">Add</a> </li>';
echo '<li id="li_admin_delete">
   <a id = "admin_delete_a" href="/design.php?tab=admin:admin_delete" class="inactivetab">Edit/Delete</a> </li>';
echo '<li id="li_admin_view">  
   <a id = "admin_view_a" href="/design.php?tab=admin:admin_view" class="inactivetab">View</a> </li>';
echo '<li id="li_admin_backtracker">
   <a id = "admin_backtracker_a" href="/Admin/Backtracker/backtracker.php?tab=admin:admin_backtracker" class="inactivetab">Backdater</a></li>';
echo '<li id="li_admin_config">
   <a id = "admin_config_a" href="/design.php?tab=admin:admin_config" class="inactivetab">Configuration</a></li>';
if ($_SESSION['sales']) {
echo '<li id="li_admin_sales">  
   <a id = "admin_sales_a" href="/design.php?tab=admin:admin_sales" class="inactivetab">Sales</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_config" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_admin_backup">
   <a id = "admin_backup_a" href="/Admin/DBBackup/backup.php?tab=admin:admin_config:admin_backup" class="inactivetab">Backup</a></li>
<li id="li_config">
   <a id = "config_a" href="/Admin/Config/config.php?tab=admin:admin_config:config" class="inactivetab">Configure</a></li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_add" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_add'].'">';
echo '<ul>';
if ($_SESSION['harvlist']) {
echo '<li id="li_admin_harvestlist">  
   <a href="/Admin/adminHarvest/harvAdminChooseDate.php?tab=admin:admin_add:admin_harvestlist" id = "admin_harvestlist_a" class="inactivetab">Harvest&nbsp;List</a> </li>';
}
echo '<li id="li_admin_addcrop">  
   <a href="/design.php?tab=admin:admin_add:admin_addcrop" id = "admin_addcrop_a" class="inactivetab">Crop';
echo '</a> </li>';
echo '<li id="li_admin_addequip">  
   <a href="/design.php?tab=admin:admin_add:admin_addequip" id = "admin_addequip_a" class="inactivetab">Equipment</a> </li>';
if ($_SESSION['soil']) {
echo '<li id="li_admin_addsoil">
   <a href="/design.php?tab=admin:admin_add:admin_addsoil" id="admin_addsoil_a" class="inactivetab">Soil</a></li>';
}
if ($_SESSION['sales']) {
echo '<li id="li_admin_addsales">
   <a href="/design.php?tab=admin:admin_add:admin_addsales" id="admin_addsales_a" class="inactivetab">Sales</a></li>';
}
echo '<li id="li_admin_addother">  
   <a href="/design.php?tab=admin:admin_add:admin_addother" id = "admin_addother_a" class="inactivetab">Other</a> </li>';
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="admin_addsales" style="display:none;">
<div class="tabs tabs<?php echo $_SESSION['num_add_sales'];?>">
<ul>
<li id="li_addproduct">  
   <a href="/Admin/Add/addProduct.php?tab=admin:admin_add:admin_addsales:addproduct" id = "addproduct_a" class="inactivetab">Product</a> </li>
<li id="li_addtarget">  
   <a href="/Admin/Add/addTarget.php?tab=admin:admin_add:admin_addsales:addtarget" id = "addtarget_a" class="inactivetab">Sales Target</a> </li>
<li id="li_addtargetemail">  
   <a href="/Admin/Add/addTargetEmail.php?tab=admin:admin_add:admin_addsales:addtargetemail" id = "addtargetemail_a" class="inactivetab">Target Email</a> </li>
<li id="li_addfarmemail">  
   <a href="/Admin/Add/addFarmEmail.php?tab=admin:admin_add:admin_addsales:addfarmemail" id = "addfarmemail_a" class="inactivetab">Farm Email</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_addcrop" style="display:none;">
<div class="tabs tabs<?php echo $_SESSION['num_add_crop'];?>">
<ul>
<li id="li_addcrop">  
   <a href="/Admin/Add/addCrop.php?tab=admin:admin_add:admin_addcrop:addcrop" id = "addcrop_a" class="inactivetab">Crop</a> </li>
<li id="li_addunit">  
   <a href="/Admin/Add/extAddUnits.php?tab=admin:admin_add:admin_addcrop:addunit" id = "addunit_a" class="inactivetab">Harvest&nbsp;Unit</a> </li>
<li id="li_addunitconv">  
   <a href="/Admin/Add/addUnits.php?tab=admin:admin_add:admin_addcrop:addunitconv" id = "addunitconv_a" class="inactivetab">Unit&nbsp;to&nbsp;Crop</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_addequip" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_add_equip'].'">';
echo '<ul>';
if ($_SESSION['tillage']) {
echo '<li id="li_addtractor">  
   <a href="/Admin/Add/addTractor.php?tab=admin:admin_add:admin_addequip:addtractor" id = "addtractor_a" class="inactivetab">Tractor</a> </li>';
echo '<li id="li_addequip:addtool">  
   <a href="/Admin/Add/addTool.php?tab=admin:admin_add:admin_addequip:addtool" id = "addtool_a" class="inactivetab">Tool</a> </li>';
}
echo '<li id="li_addequip:addflat">
   <a href="/Admin/Add/addFlat.php?tab=admin:admin_add:admin_addequip:addflat" id = "addflat_a" class="inactivetab">Tray</a> </li>';
if ($_SESSION['irrigation']) {
echo '<li id="li_addequip:addirr">
   <a href="/Admin/Add/addIrrDevice.php?tab=admin:admin_add:admin_addequip:addirr" id = "addirr_a" class="inactivetab">Irr.&nbsp;Device</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="admin_addsoil" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_add_soil'].'">';
echo '<ul>';
if ($_SESSION['fertilizer']) {
echo '<li id="li_admin_addfert">
   <a href="/design.php?tab=admin:admin_add:admin_addsoil:admin_addfert" id="admin_addfert_a" class="inactivetab">Fertilizer</a></li>';
}
if ($_SESSION['spraying']) {
echo '<li id="li_addspraymaterial">
   <a href="/Admin/Add/addSprayMaterial.php?tab=admin:admin_add:admin_addsoil:addspraymaterial" id="addspraymaterial_a" class="inactivetab">Spray&nbsp;Material</a></li>';
}
if ($_SESSION['cover']) {
echo '<li id="li_addcovercrop">  
   <a href="/Admin/Add/addCover.php?tab=admin:admin_add:admin_addsoil:addcovercrop" id = "addcovercrop_a" class="inactivetab">Cover Crop</a> </li>';
}
if ($_SESSION['compost']) {
echo '<li id="li_addcompost">
   <a href="/design.php?tab=admin:admin_add:admin_addsoil:addcompost" id="addcompost_a" class="inactivetab">Compost</a></li>';
}
if ($_SESSION['scouting']) {
echo '<li id="li_addspecies">  
   <a href="/design.php?tab=admin:admin_add:admin_addsoil:addspecies" id = "addspecies_a" class="inactivetab">Species</a> </li>';
}
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="addspecies" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_scout'].'">';
echo '<ul>';
if ($_SESSION['insect']) {
echo '<li id="li_addpest">  
   <a href="/Admin/Add/addPest.php?tab=admin:admin_add:admin_addsoil:addspecies:addpest" id = "addpest_a" class="inactivetab">Insect</a> </li>';
}
if ($_SESSION['weed']) {
echo '<li id="li_addweed">  
   <a href="/Admin/Add/addWeed.php?tab=admin:admin_add:admin_addsoil:addspecies:addweed" id = "addweed_a" class="inactivetab">Weed</a> </li>';
}
if ($_SESSION['disease']) {
echo '<li id="li_add_disease">  
   <a href="/Admin/Add/addDisease.php?tab=admin:admin_add:admin_addsoil:addspecies:add_disease" id = "add_disease_a" class="inactivetab">Disease</a> </li>';
}
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="admin_addfert" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_fertilizer'].'">';
echo '<ul>';
if ($_SESSION['dryfertilizer']) {
echo '<li id="li_adddryfertilizer">
   <a href="/Admin/Add/addDryFertilizer.php?tab=admin:admin_add:admin_addsoil:admin_addfert:adddryfertilizer" id="adddryfertilizer_a" class="inactivetab">Dry Fertilizer</a></li>';
}
if ($_SESSION['liquidfertilizer']) {
echo '<li id="li_addliquidfertilizer">
   <a href="/Admin/Add/addLiquidFertilizer.php?tab=admin:admin_add:admin_addsoil:admin_addfert:addliquidfertilizer" id="addliquidfertilizer_a" class="inactivetab">Liquid Fertilizer</a></li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="addcompost" style="display:none;">
<div class="tabs tabs5">
<ul>
<li id="li_addpile">  
   <a href="/Admin/Add/addPileID.php?tab=admin:admin_add:admin_addsoil:addcompost:addpile" id = "addpile_a" class="inactivetab">Pile</a> </li>
<li id="li_addcompostactivity">  
   <a href="/Admin/Add/addCompostActivity.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostactivity" id = "addcompostactivity_a" class="inactivetab">Activity</a> </li>
<li id="li_addcompostmaterial">  
   <a href="/Admin/Add/addCompostMaterial.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostmaterial" id = "addcompostmaterial_a" class="inactivetab">Material</a> </li>
<li id="li_addcompostunit">  
   <a href="/Admin/Add/addCompostUnit.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostunit" id = "addcompostunit_a" class="inactivetab">Unit</a> </li>
<li id="li_addcompostconv">  
   <a href="/Admin/Add/addCompostConversion.php?tab=admin:admin_add:admin_addsoil:addcompost:addcompostconv" id = "addcompostconv_a" class="inactivetab">Conversion</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_addother" style="display:none;">
<div class="tabs tabs<?php echo $_SESSION['num_add_other'];?>">
<ul>
<li id="li_adduser">  
   <a href="/Admin/Add/add<?php if ($farm != "dfarm") {echo "Ext";}?>User.php?tab=admin:admin_add:admin_addother:adduser" id = "adduser_a" class="inactivetab">User</a> </li>
<li id="li_addfield">  
   <a href="/Admin/Add/addField.php?tab=admin:admin_add:admin_addother:addfield" id = "addfield_a" class="inactivetab">Field</a> </li>
<?php
if ($_SESSION['labor']) {
echo '<li id="li_addtask">  
   <a href="/Admin/Add/addTask.php?tab=admin:admin_add:admin_addother:addtask" id = "addtask_a" class="inactivetab">Labor&nbsp;Task</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>

<div id="admin_delete" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_edit'].'">';
echo '<ul>';
echo '<li id="li_deletecrop">  
   <a href="/design.php?tab=admin:admin_delete:deletecrop" id = "deletecrop_a" class="inactivetab">Crop</a> </li>';
echo '<li id="li_deleteequip">  
   <a href="/design.php?tab=admin:admin_delete:deleteequip" id = "deleteequip_a" class="inactivetab">Equipment</a> </li>';
if ($_SESSION['num_edit_soil_material'] > 0) {
echo '<li id="li_deletematerials">  
   <a href="/design.php?tab=admin:admin_delete:deletematerials" id = "deletematerials_a" class="inactivetab">Material</a> </li>';
}
if ($_SESSION['sales']) {
echo '<li id="li_deletesales">  
   <a href="/design.php?tab=admin:admin_delete:deletesales" id = "deletesales_a" class="inactivetab">Sales</a> </li>';
}
echo '<li id="li_deleteother">  
   <a href="/design.php?tab=admin:admin_delete:deleteother" id = "deleteother_a" class="inactivetab">Other</a> </li>';
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="deletesales" style="display:none;">
<div class="tabs tabs4">
<ul>
<li id="li_delete_product">  
   <a href="/Admin/Delete/deleteProduct.php?tab=admin:admin_delete:deletesales:delete_product" id="delete_product_a" class="inactivetab">Product</a> </li>
<li id = "li_delete_target">
   <a href="/Admin/Delete/deleteTarget.php?tab=admin:admin_delete:deletesales:delete_target" id="delete_target_a" class="inactivetab">Sales Target</a> </li>
<li id="li_delete_targetemail">  
   <a href="/Admin/Delete/targetEmailTable.php?tab=admin:admin_delete:deletesales:delete_targetemail" id="delete_targetemail_a" class="inactivetab">Target Email</a> </li>
<li id="li_delete_farmemail">  
   <a href="/Admin/Delete/farmEmailTable.php?tab=admin:admin_delete:deletesales:delete_farmemail" id="delete_farmemail_a" class="inactivetab">Farm Email</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>



<div id="deletematerials" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_edit_soil_material'].'">';
echo '<ul>';
if ($_SESSION['dryfertilizer']) {
echo '<li id="li_deletedryfertilizermaterial">
	<a href="/Admin/Delete/deleteDryFertilizer.php?tab=admin:admin_delete:deletematerials:deletedryfertilizermaterial" id="deletedryfertilizermaterial_a" class="inactivetab">Dry Fertilizer</a></li>';
}
if ($_SESSION['liquidfertilizer']) {
echo '<li id="li_deleteliquidfertilizermaterial">
	<a href="/Admin/Delete/deleteLiquidFertilizer.php?tab=admin:admin_delete:deletematerials:deleteliquidfertilizermaterial" id="deleteliquidfertilizermaterial_a" class="inactivetab">Liquid Fertilizer</a></li>';
}
if ($_SESSION['spraying']) {
echo '<li id="li_deletespraymaterial">
   <a href="/Admin/Delete/deleteSprayMaterial.php?tab=admin:admin_delete:deletematerials:deletespraymaterial" id="deletespraymaterial_a" class="inactivetab">Spray Material</a></li>';
}
echo '</ul>';
?>
<?php createBR(); ?>
</div>
</div>

<div id="deletecrop" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_deleteunit">  
   <a href="/Admin/Add/viewUnits.php?tab=admin:admin_delete:deletecrop:deleteunit" id = "deleteunit_a" class="inactivetab">Crop&nbsp;Unit</a> </li>
<li id="li_deleteplant">
   <a href="/Admin/Delete/deletePlant.php?tab=admin:admin_delete:deletecrop:deleteplant" id="deleteplant_a" class="inactivetab">Crop</a></li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="deleteequip" style="display:none;">
<?php
if ($_SESSION['tillage']) {
   echo '<div class="tabs tabs2">';
} else {
   echo '<div class="tabs tabs1">';
}
?>
<ul>
<li id="li_editflat">  
   <a href="/Admin/Delete/deleteFlat.php?tab=admin:admin_delete:deleteequip:editflat" id = "editflat_a" class="inactivetab">Tray&nbsp;Size</a> </li>
<?php
if ($_SESSION['tillage']) {
echo '<li id="li_deletetractor">  
   <a href="/Admin/Delete/deleteTractor.php?tab=admin:admin_delete:deleteequip:deletetractor" id = "deletetractor_a" class="inactivetab">Tractor</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="deleteother" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_edit_other'].'">';
echo '<ul>';
echo '<li id="li_editfield">  
   <a href="/Admin/Add/editField.php?tab=admin:admin_delete:deleteother:editfield" id = "editfield_a" class="inactivetab">Field</a> </li>';
echo '<li id="li_edituser">  <a href="/Admin/Add/';
if ($farm == "dfarm") {
  echo 'editUser.php';
} else {
   echo 'resetPassword.php';
}
echo '?tab=admin:admin_delete:deleteother:edituser" id = "edituser_a" class="inactivetab">User</a> </li>';
if ($_SESSION['labor']) {
echo '<li id="li_deletelaborT">  
   <a href="/Admin/Delete/deleteTask.php?tab=admin:admin_delete:deleteother:deletelaborT" id = "deletelaborT_a" class="inactivetab">Labor&nbsp;Task</a> </li>';
}
if ($_SESSION['compost']) {
echo '<li id="li_deletecompostpile">
	<a href="/Admin/Delete/compostPileTable.php?tab=admin:admin_delete:deleteother:deletecompostpile" id="deletecompostpile_a" class="inactivetab">Compost&nbsp;Pile</a></li>';
}
if ($_SESSION['cover']) {
echo '<li id="li_deletecoverCrop">  
   <a href="/Admin/Delete/deleteCoverCrop.php?tab=admin:admin_delete:deleteother:deletecoverCrop" id = "deletecoverCrop_a" class="inactivetab">Cover&nbsp;Crop</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>


<div id="admin_view" style="display:none;">
<div class="tabs tabs3">
<ul>
<li id="li_view_tables">  
   <a href="/design.php?tab=admin:admin_view:view_tables" id = "view_tables_a" class="inactivetab">Tables</a> </li>
<li id="li_view_graphs">  
   <a href="/design.php?tab=admin:admin_view:view_graphs" id = "view_graphs_a" class="inactivetab">Graphs</a> </li>
<li id="li_viewfiles">  
   <a href="/wfb.php?tab=admin:admin_view:viewfiles" id = "viewfiles_a" class="inactivetab">Files</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="view_graphs" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_view_graphs'].'">';
?>
<ul>
<li id="li_yieldprf">
	<a href="/Admin/View/yieldPerRfReport.php?tab=admin:admin_view:view_graphs:yieldprf&file=yieldPerRf.php" id = "yieldprf_a" class="inactivetab">Yield/Row&nbsp;Foot</a> </li>
<li id="li_sumyield">
	<a href="/Admin/View/yieldPerRfReport.php?tab=admin:admin_view:view_graphs:sumyield&file=totalYield.php" id = "sumyield_a" class="inactivetab">Total&nbsp;Yield</a> </li>
<li id="li_quantharvested">
	<a href="/Admin/View/quantHarvestedReport.php?tab=admin:admin_view:view_graphs:quantharvested" id = "quantharvested_a" class="inactivetab">Quantity&nbsp;Harvested</a> </li>
<?php
if ($_SESSION['sales_invoice']) {
echo '<li id="li_invoice_graph">
	<a href="/Admin/View/invoiceReport.php?tab=admin:admin_view:view_graphs:invoice_graph" id = "invoice_graph_a" class="inactivetab">Invoice</a> </li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="view_tables" style="display:none;">
<div class="tabs tabs5">
<ul>
<li id="li_viewfield">  
   <a href="/Admin/View/fieldReport.php?tab=admin:admin_view:view_tables:viewfield" id = "viewfield_a" class="inactivetab">Field</a> </li>
<li id="li_viewunits">  
   <a href="/Admin/Add/view<?php if ($farm=='wahlst_spiralpath') {echo "Ext";}?>Units.php?tab=admin:admin_view:view_tables:viewunits" id = "viewunits_a" class="inactivetab">Units</a> </li>
<li id="li_viewcrops">
   <a href="/Admin/View/viewCrops.php?tab=admin:admin_view:view_tables:viewcrops" id="viewcrops_a" class="inactivetab">Crops</a></li>
<li id="li_viewfieldrecord">  
   <a href="/Admin/View/fieldRecordReport.php?tab=admin:admin_view:view_tables:viewfieldrecord" id = "viewfieldrecord_a" class="inactivetab">Field Record</a> </li>
<li id="li_view_history">  
   <a href="/Admin/View/cropHistory.php?tab=admin:admin_view:view_tables:view_history" id = "view_history_a" class="inactivetab">Crop History</a> </li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="admin_sales" style="display:none;">
<?php
echo '<div class="tabs tabs'.$_SESSION['num_sales'].'">';
echo '<ul>';
if ($_SESSION['sales_packing']) {
echo '<li id="li_packing">
	<a href="/design.php?tab=admin:admin_sales:packing" id="packing_a" class="inactivetab">Packing</a></li>';
echo '<li id="li_distribution">
	<a href="/design.php?tab=admin:admin_sales:distribution" id="distribution_a" class="inactivetab">Distribution</a></li>';
echo '<li id="li_inventory">
	<a href="/Admin/Sales/Inventory/inventoryReport.php?tab=admin:admin_sales:inventory" id="inventory_a" class="inactivetab">Inventory</a></li>';
}
if ($_SESSION['sales_invoice']) {
echo '<li id="li_invoice">
	<a href="/design.php?tab=admin:admin_sales:invoice" id="invoice_a" class="inactivetab">Invoice</a></li>';
}
?>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="packing" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_packing_table">
	<a href="/Admin/Sales/Packing/packing.php?tab=admin:admin_sales:packing:packing_input" id="packing_input_a" class="inactivetab">Input Form</a></li>
<li id="li_packing_report">
	<a href="/Admin/Sales/Packing/packingReport.php?tab=admin:admin_sales:packing:packing_report" id="packing_report_a" class="inactivetab">Packing Report</a></li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="distribution" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_distribution_table">
	<a href="/Admin/Sales/Distribution/distribution.php?tab=admin:admin_sales:distribution:distribution_input" id="distribution_input_a" class="inactivetab">Input Form</a></li>
<li id="li_distribtuion_report">
	<a href="/Admin/Sales/Distribution/distributionReport.php?tab=admin:admin_sales:distribution:distribution_report" id="distribution_report_a" class="inactivetab">Distribution Report</a></li>
</ul>
<?php createBR(); ?>
</div>
</div>

<div id="invoice" style="display:none;">
<div class="tabs tabs3">
<ul>
<li id="li_createinvoice">  
   <a href="/Admin/Sales/Invoice/invoiceChooseDate.php?exist=0&tab=admin:admin_sales:invoice:createinvoice" id = "createinvoice_a" class="inactivetab">Create</a> </li>
<li id="li_editinvoice">  
   <a href="/Admin/Sales/Invoice/invoiceChooseDate.php?exist=1&tab=admin:admin_sales:invoice:editinvoice" id = "editinvoice_a" class="inactivetab">Edit</a> </li>
<li id="li_invoicereport">  
   <a href="/Admin/Sales/Invoice/salesReport.php?tab=admin:admin_sales:invoice:invoicereport" id = "invoicereport_a" class="inactivetab">Report</a> </li>
</ul>
<?php createBR(); ?>
<!--
<br clear="all"/>
-->
</div>
</div>
