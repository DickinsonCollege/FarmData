<?php session_start(); ?>
<!--TEST GRAPH -->
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
	$file = $_GET['file'];
?>
<center><h2 class="hi"> Select Harvest Records: </h2></center>

<form class = "pure-form pure-form-aligned" method="GET" action="<?php echo $file;?>">
	<?PHP
		$tab="yieldprf";
		if ($file=='totalYield.php'){$tab="sumyield";}
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:'.$tab.'">';
   ?>
<div class = "pure-control-group">
<label for="year">Year to Graph:</label>
   <select name="year" id="year" onChange="addFieldID()">
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

   <div class = "pure-control-group">
   <label for="crop"> Crop:</label>
   <select id = "crop" name="crop" class='mobile-select'>
   <?php
   $result = $dbcon->query("SELECT distinct crop from harvested");
   while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
      echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
   }
   ?>
   </select>
   </div>
   <br clear="all">
   <input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit" />
</form>

