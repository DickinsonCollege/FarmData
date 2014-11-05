<?php session_start(); ?>
<!--TEST GRAPH -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h1 class="hi"> Select Harvest Records: </h1>

<form method="GET" action="quantHarvested.php">
	<?PHP
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:quantharvested">';
   ?>
	<?php
	echo '<label for="from">From:&nbsp;</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date.php';
	echo '<br clear="all"/>';
	echo '<label for="to"> To:&nbsp</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
	?>
	<br clear="all">
	<br clear="all">
   <label for="crop"> Crop:&nbsp;</label>
   <div class ="styled-select">
   <select id = "crop" name="crop" class="mobile-select">
   <?php
   $result = mysql_query("SELECT distinct crop from harvested");
   while ($row1 =  mysql_fetch_array($result)){
      echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
   }
   ?>
   </select>
   </div>
	<br clear="all">
	<br clear="all">
   <input class="submitbutton" type="submit" name="submit" value="Submit" />
</form>

