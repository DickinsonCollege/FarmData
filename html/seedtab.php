<div id="seeding" style="display:none;">
<?php
$order = $_SESSION['admin'] && $_SESSION['seed_order'];
if ($order) {
   echo '<div class="tabs tabs4">';
} else {
   echo '<div class="tabs tabs3">';
}
?>
<ul>
<li id="li_direct">  
   <a id = "direct_a" href="/design.php?tab=seeding:direct" class="inactivetab">Direct&nbsp;Seeding</a> </li>
<li id="li_flats">  
   <a id = "flats_a" href="/design.php?tab=seeding:flats" class="inactivetab">Tray&nbsp;Seeding</a> </li>
<li id="li_transplant">  
   <a id = "transplant_a" href="/design.php?tab=seeding:transplant" class="inactivetab">Transplanting</a> </li>
<?php
if ($order) {
echo '<li id="li_ordert">  
   <a id = "ordert_a" href="/design.php?tab=seeding:ordert" class="inactivetab">Order</a> </li>';
}
?>
</ul>
<!--
<?php createBR(); ?>
-->
<br clear="all"/>
</div>
</div>

<div id="ordert" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_ordert_input">  
   <a id = "ordert_input_a" class="inactivetab" a href="/Seeding/Order/chooseCrop.php?tab=seeding:ordert:ordert_input">Input Form</a> </li>
<li id="li_ordert_report">  
   <a id = "ordert_report_a" class="inactivetab" a href="/Seeding/Order/orderReport.php?tab=seeding:ordert:ordert_report">Report</a> </li>
</ul>
<!--
<br clear="all"/>
-->
<?php createBR(); ?>
</div>
</div>

<div id="direct" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_direct_input">  
   <a id = "direct_input_a" class="inactivetab" a href="/Seeding/dir_seeding.php?tab=seeding:direct:direct_input">Input Form</a> </li>
<li id="li_direct_report">  
   <a id = "direct_report_a" class="inactivetab" a href="/Seeding/plantReport.php?tab=seeding:direct:direct_report">Report</a> </li>
</ul>
<!--
<br clear="all"/>
-->
<?php createBR(); ?>
</div>
</div>

<div id="flats" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_flats_input">  
   <a id = "flats_input_a" class="inactivetab" a href="/Seeding/gh_seeding.php?tab=seeding:flats:flats_input">Input Form</a> </li>
<li id="li_flats_report">  
   <a id = "flats_report_a" class="inactivetab" a href="/Seeding/gh_seedingReport.php?tab=seeding:flats:flats_report">Report</a> </li>
</ul>
<!--
<br clear="all"/>
-->
<?php createBR(); ?>
</div>
</div>

<div id="transplant" style="display:none;">
<div class="tabs tabs2">
<ul>
<li id="li_transplant_input">  
   <a id = "transplant_input_a" class="inactivetab" a href="/Seeding/transferred_to.php?tab=seeding:transplant:transplant_input">Input Form</a> </li>
<li id="li_transplant_report">  
   <a id = "transplant_report_a" class="inactivetab" a href="/Seeding/transplantReport.php?tab=seeding:transplant:transplant_report">Report</a> </li>
</ul>
<!--
<br clear="all"/>
-->
<?php createBR(); ?>
</div>
</div>
