<?php session_start(); ?>
<!--TEST GRAPH -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
	$file = $_GET['file'];
?>
<h1 class="hi"> Select Harvest Records: </h1>

<form method="GET" action="<?php echo $file;?>">
	<?PHP
		$tab="yieldprf";
		if ($file=='totalYield.php'){$tab="sumyield";}
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:'.$tab.'">';
   ?>
	<label for="year">Year to Graph:&nbsp;</label>
   <div class="styled-select"><select name="year" id="year" onChange="addFieldID()">
   <?php
   if (!$dYear) {
      $curYear = strftime("%Y");
   } else {
      $curYear = $dYear;
   }
   echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
   for($year = $curYear - 3; $year < $curYear+5; $year++) {
      echo "\n<option value =\"$year\">$year</option>";
   }
   ?>
   </select></div>
	<br clear="all">
	<br clear="all">
   <label for="crop"> Crop:&nbsp;</label>
   <div class ="styled-select">
   <select id = "crop" name="crop" class='mobile-select'>
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

