<?php session_start();?>
<?php
/*
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
*/

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlget = "SELECT id, year(comDate) as yr, month(comDate) as mth, day(comDate) as dy, username,".
   "comments FROM comments where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$user = $row['username'];
$comments = $row['comments'];
$curMonth = $row['mth'];
$curYear = $row['yr'];
$curDay = $row['dy'];
?>

<?php
echo "<form name='form' class='pure-form pure-form-aligned' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=harvest:harvestReport&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&fieldID="."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&id=".$id."\">";

echo "<center>";
echo "<H2> Edit Comments</H2>";
echo "</center>";
echo '<fieldset>';

echo '<div class="pure-control-group">';
echo '<label>Date:</label>';

echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
echo '</select>';

echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
echo '</select>';

echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>User:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
/*
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
} else {
   echo $useropts;
}
*/
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo '</div>';
echo '<br clear="all"/>';
?>

<?php
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo '<fieldset>';
echo "</form>";
if ($_POST['submit']) {
   $comSanitized = escapehtml($_POST['comments']);
   $sql = "update comments set comments='".$comSanitized;
   $sql .= "' where id=".$id;
   $result = mysql_query($sql);
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=notesTable.php?year=".$origYear.'&month='
        .$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
        "&tab=notes:notes_report&submit=Submit\">";
   }
}
?>
