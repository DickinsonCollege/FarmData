<div id="harvest" style="display:none;">
<?php
if ($_SESSION['harvlist']) {
   echo '<div class="tabs tabs3">';
} else {
   echo '<div class="tabs tabs2">';
}
?>
<ul>
<li id="li_harvestInput">  
   <a href="/Harvest/harvest.php?tab=harvest:harvestInput" id = "harvestInput_a" class="inactivetab">Input Form</a> </li>
<li id="li_harvestReport">  
   <a href="/Harvest/harvestReport.php?tab=harvest:harvestReport" id = "harvestReport_a" class="inactivetab">Report</a> </li>
<?php
if ($_SESSION['harvlist']) {
   echo '<li id="li_harvestList">  
   <a href="/Harvest/harvChooseDate.php?tab=harvest:harvestList" id = "harvestList_a" class="inactivetab">Harvest&nbsp;List</a> </li>';
}
?>
</ul>

<?php createBR(); ?>

<!--
<br clear="all"/>
-->
</div>
</div>
