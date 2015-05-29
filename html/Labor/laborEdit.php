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

include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$id=$_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$origCrop = $_GET['crop'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$origField = $_GET['fieldID'];
$origTask = $_GET['task'];
$sqlget = "SELECT id,year(ldate) as yr, month(ldate) as mth, day(ldate) as dy, crop, username,".
   "ldate,fieldID,task,hours, comments FROM labor where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$user = $row['username'];
$field = $row['fieldID'];
$task = $row['task'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrop = $row['crop'];
$comments = $row['comments'];
$hours = $row['hours'];

echo "<form name='form' class = 'pure-form pure-form-aligned' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=labor:labor_report&year=".$origYear."&month=".$origMonth.
   "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&fieldID=".encodeURIComponent($origField).
   "&task=".encodeURIComponent($origTask).
   "&crop=".encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<center><h2> Edit Labor Record </h2></center>";
echo '<br clear="all"/>';
echo '<div class = "pure-control-group">';
echo '<label>Date:</label>';
echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day < 32; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 3; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo '<div class = "pure-control-group">';
echo '<label>Crop:</label>';
echo '<select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select></div>';

echo '<div class = "pure-control-group">';
echo '<label>Field:</label>';
echo '<select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die("ERROR3");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</select></div>';

echo '<div class = "pure-control-group">';
echo '<label>User:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   $sqldata = mysql_query($sql) or die("ERROR4");
   while ($row = mysql_fetch_array($sqldata)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].' </option>';
   }
} else {
   echo $useropts;
}
echo '</select></div>';

echo '<div class = "pure-control-group">';
echo '<label>Task:</label>';
echo '<select name="task" id="task">';
echo '<option value="'.$task.'" selected>'.$task.' </option>';
$sqldata = mysql_query("select task from task") or die(mysql_error());
while ($row = mysql_fetch_array($sqldata)){
	echo '<option value="'.$row[task].'">'.$row[task].'</option>';
}
echo '</select></div>';

echo '<div class = "pure-control-group">';
echo '<label>Hours:</label>';
echo '<input type="text" class="textbox2" name="hours" id="hours" value="'.$hours.'">';
echo '</div>';

echo '<div class = "pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';

echo '<br clear="all"/>';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
echo '<br clear = "all">';

if ($_POST['submit']) {
   $comSanitized=escapehtml($_POST['comments']);
   $task = escapehtml($_POST['task']); echo $task;
   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   $hours = escapehtml($_POST['hours']);
   if ($hours == "") {
      $hours = 0;
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $user = escapehtml($_POST['user']);
   $sql = "update labor set username='".$user."', fieldID='".$fld."', ldate='".$year."-".
     $month."-".$day."',task='".$task."',hours=".$hours.",comments='".
     $comSanitized."',crop='".$crop."' where id=".$id;
   $result = mysql_query($sql);
// START - check if old crop can be deleted first!!!
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error().$sql."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=laborTable.php?year='.
         $origYear.'&month='.$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.
         '&tmonth='.$tcurMonth.'&tday='.$tcurDay.
         "&crop=".encodeURIComponent($origCrop).
         "&fieldID=".encodeURIComponent($origField)."&task=".
         encodeURIComponent($origTask)."&tab=labor:labor_report\">";
   }
}
?>
