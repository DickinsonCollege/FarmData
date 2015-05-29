<?php session_start(); ?>
<!--TEST GRAPH -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center><h2 class="hi"> Select Harvest Records: </h2></center>

<form class = "pure-form pure-form-aligned" method="GET" action="quantHarvested.php">
	<?PHP
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:quantharvested">';
   ?>
	<?php
	echo '<div class = "pure-control-group">';
	echo '<label for="from">From:</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date.php';
	echo '</div>';

	echo '<div class = "pure-control-group">';
	echo '<label for="to">To:</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
	echo '</div>';
	?>

   <div class = "pure-control-group">
   <label for="crop"> Crop:</label>
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
   <input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit" />
</form>

