<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>

<form name='date' method='POST'>
<?php 
echo '<h3> Choose the date of the harvest list';
echo ' you would like to view. </h3>';
echo '<br clear="all">';
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo "<br clear=\"all\">";
?>
<br clear="all"/>

<input class="submitbutton" type="submit" name="date" value="Choose" >
</form>

<?php
if(isset($_POST['date'])){
	$day=$_POST['day'];
	$month=$_POST['month'];
	$year=$_POST['year'];
	echo "<br>";
	$listDate=$year."-".$month."-".$day;
	echo "<br>";

	$sql_result=mysql_query("SELECT id FROM harvestList WHERE harDate='$listDate'");

	if(is_resource($sql_result) &&  mysql_num_rows($sql_result) > 0 ){
   	 $sql_result = mysql_fetch_array($sql_result);
    	 $currentID= $sql_result["id"];
	}else{
	 $sql="INSERT INTO harvestList(harDate, comment) VALUES('".$listDate."','')";
	echo "<br>";
	mysql_query($sql);
	$currentIDTable=mysql_query("SELECT LAST_INSERT_ID()");
	$currentIDRow=mysql_fetch_array($currentIDTable);
	"The currentID ". $currentID= $currentIDRow['LAST_INSERT_ID()'];
	}	

	echo ' <meta http-equiv="refresh" content=0;URL="harvestList.php?tab=harvest:harvestList&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.$currentID.'&detail=0 ">';

}
?>
