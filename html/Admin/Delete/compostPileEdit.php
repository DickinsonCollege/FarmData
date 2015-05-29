<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$origPileID = $_GET['pileID'];

$sqlget = "SELECT pileID, comments, active FROM compost_pile where pileID='".$origPileID."'";
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$pileID = $row['pileID'];
$comments = $row['comments'];
$active = escapehtml($row['active']);

if ($active == 1) {
	$activeText = "Yes";
} else {
	$activeText = "No";
}
?>

<?php
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deleteother:deletecompost&pileID=".encodeURIComponent($pileID)."\">";

echo "<center>";
echo "<H2> Edit Compost Record </H2>";
echo "</center>";

echo "<div class='pure-control-group'>";
echo '<label>Name of Pile:</label>';
echo "<input type='text' id='pileID' name='pileID' value=\"".$pileID."\" class='textbox25'>";
echo "</div>";

echo "<div class='pure-control-group'>";
echo "<label>Active:</label>";
echo "<select name='active' id='active' class='mobile-select'>";
echo "<option value='".$activeText."'>".$activeText."</option>";
echo "<option value='Yes'>Yes</option>";
echo "<option value='No'>No</option>";
echo "</select>";
echo "</div>";

echo "<div class='pure-control-group'>";
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo "</div>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';


echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if ($_POST['submit']) {
	$comments = escapehtml($_POST['comments']);
	$pileID = escapehtml($_POST['pileID']);
	$active = escapehtml($_POST['active']);
	if ($active === "Yes") {
		$active = 1;
	} else {
		$active = 0;
	}

	$sql = "UPDATE compost_pile
		SET pileID='".$pileID."', comments='".$comments."', active=".$active." 
		WHERE pileID='".$origPileID."'";
   $result = mysql_query($sql);
   
	if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>alert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;';
     echo 'URL=compostPileTable.php?tab=admin:admin_delete:deleteother:deletecompostpile">';
   }
}
?>
