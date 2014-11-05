<?php session_start();?>
<?php

$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   $dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or 
       die ("Connect Failed! :".mysql_error());
   mysql_select_db('wahlst_users');
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   $result = mysql_query($sql);
   echo mysql_error();
   $useropts='';
   while ($row = mysql_fetch_array($result)) {
      $useropts.='<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
}

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origCrop = $_GET['crop'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(seedDate) as yr, month(seedDate) as mth, day(seedDate) as dy, crop, username, 
	seedDate, numseeds_planted, comments, varieties, flats, cellsFlat FROM gh_seeding where id = ".$id;

$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$id = $row['id'];
$curDay = $row['dy'];
$curMonth = $row['mth'];
$curYear = $row['yr'];
$user = $row['username'];
$curCrop = $row['crop'];
$seedDate = $row['seedDate'];
$numseeds_planted = $row['numseeds_planted'];
$comments = $row['comments'];
$varieties = $row['varieties'];
$flats = $row['flats'];
$cellsFlat = $row['cellsFlat'];

echo "<form name='form' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deleteseed:deleteflats&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".
   encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<H3> Edit GreenHouse Seeding Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</div></select>';
echo '<br clear="all"/>';

echo '<label>Username:&nbsp;</label>';
echo '<div class="styled-select"><select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.'</option>';
if ($farm == 'dfarm') {
	$sql = 'select username from users where active = 1';
	$sqldata = mysql_query($sql) or die("ERROR3");
	while ($row = mysql_fetch_array($sqldata)) {
		echo '<option value="'.$row['username'].'">'.$row['username'].'</option>';
	}
} else {
	echo $useropts;
}
echo '</div></select>';
echo '<br clear="all">';

echo '<label>Crop:&nbsp</label>';
echo '<div class="styled-select"><select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

echo "<label>Number of Seeds Planted:&nbsp;</label>";
echo "<input type='text' class='textbox2' name='numseeds_planted' id='numseeds_planted' value='".$numseeds_planted."'>";
echo "<br clear='all'/>";

echo "<label>Varieties:&nbsp;</label>";
echo "<input type='text' class='textbox3' name='varieties' id='varieties' value='".$varieties."'>";
echo "<br clear='all'/>";

echo "<label>Flats:&nbsp;</label>";
echo "<input type='text' class='textbox2' name='flats' id='flats' value='".$flats."'>";
echo "<br clear='all'/>";

echo "<label>Cells Flat:&nbsp;</label>";
echo "<input type='text' class='textbox2' name='cellsFlat' id='cellsFlat' value='".$cellsFlat."'>";
echo "<br clear='all'/>";
echo "<br clear='all'/>";


echo '<label>Comments:&nbsp</label>';
echo '<br clear="all"/>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';

echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'>";
echo "</form>";
if ($_POST['submit']) {
	$comSanitized = escapehtml($_POST['comments']);
	$crop = escapehtml($_POST['crop']);
	$numseeds_planted = escapehtml($_POST['numseeds_planted']);
	$varieties = escapehtml($_POST['varieties']);
	$flats = escapehtml($_POST['flats']);
	$cellsFlat = escapehtml($_POST['cellsFlat']);
	$year = escapehtml($_POST['year']);
	$month = escapehtml($_POST['month']);
	$day = escapehtml($_POST['day']);
	$user = escapehtml($_POST['user']);
   
	echo $sql = "update gh_seeding set username='".$user."',crop='".$crop."', seedDate='".$year."-".
		$month."-".$day."', numseeds_planted=".$numseeds_planted.", comments='".$comSanitized."', 
		varieties='".$varieties."', flats=".$flats.", cellsFlat=".$cellsFlat." 
		WHERE id=".$id;
   $result = mysql_query($sql);
   
	if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=ghTable.php?year=".$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&crop=".encodeURIComponent($origCrop).
        "&tab=admin:admin_delete:deleteseed:deleteflats&submit=Submit\">";
   }
}
?>
