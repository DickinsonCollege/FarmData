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


$sqlget = "SELECT id,year(plantdate) as yr, month(plantdate) as mth, day(plantdate) as dy, crop, username,".
   "plantdate,fieldID,bedft,rowsBed,hours, comments FROM dir_planted where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$user = $row['username'];
$field = $row['fieldID'];
$bedftv = $row['bedft'];
$rowsBed = $row['rowsBed'];
$com = $row['comments'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrop = $row['crop'];
$comments = $row['comments'];
$hours = $row['hours'];

echo "<form name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deleteseed:deletedirplant&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop=".
    encodeURIComponent($origCrop)."&id=".$id."\">";
/*
echo '<input type="hidden" name="oldCrop" value="'.$curCrop.'">';
echo '<input type="hidden" name="oldField" value="'.$field.'">';
*/
echo "<H3> Edit Direct Planting Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day < 32; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 3; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</div></select>';
echo '<br clear="all"/>';
echo '<label>Crop:&nbsp</label>';
echo '<div class="styled-select"><select name="crop" id="crop">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant where active=1';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

echo '<label>Field:&nbsp</label>';
echo '<div class="styled-select"><select name="fieldID" id="fieldID">';
echo '<option value="'.$field.'" selected>'.$field.' </option>';
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die("ERROR3");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

echo '<label>User:&nbsp</label>';
echo '<div class="styled-select"><select name="user" id="user">';
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
echo '</div></select>';
echo '<br clear="all"/>';

echo '<label>Bed Feet:&nbsp</label>';
echo '<input type="text" class="textbox3" name="bedftv" id="bedftv" value="'.$bedftv.'">';
echo '<br clear="all"/>';

echo '<label>Rows/Bed:&nbsp</label>';
echo '<div class="styled-select"><select name="rowsbed" id="rowsbed">';
echo '<option value='.$rowsBed.' selected>'.$rowsBed.' </option>';
for ($row = 1; $row <= 7; $row++) {
   echo '<option value='.$row.'>'.$row.'</option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

if ($_SESSION['labor']) {
   echo '<label>Hours:&nbsp</label>';
   echo '<input type="text" class="textbox2" name="hours" id="hours" value="'.$hours.'">';
   echo '<br clear="all"/>';
}

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
   $comSanitized=escapehtml($_POST['comments']);
   $bedftv = escapehtml($_POST['bedftv']);
   $numrows = escapehtml($_POST['rowsbed']);
   $fld = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
      if ($hours == "") {
         $hours = 0;
      }
   }
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $user = escapehtml($_POST['user']);
   $sql = "update dir_planted set username='".$user."', fieldID='".$fld."', plantdate='".$year."-".
     $month."-".$day."', bedft=".$bedftv.",rowsBed=".$numrows.",hours=".$hours.",comments='".
     $comSanitized."',crop='".$crop."' where id=".$id;
   $result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {

      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=dirTable.php?year=".$origYear."&month=".$origMonth.
        "&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth.
        "&tday=".$tcurDay.
        "&tab=admin:admin_delete:deleteseed:deletedirplant"
        ."&crop=".encodeURIComponent($origCrop)."\">";
   }
}
?>
