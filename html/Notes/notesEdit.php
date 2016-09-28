<?php session_start();?>
<?php
$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   try {
      $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass',
         array(PDO::MYSQL_ATTR_INIT_COMMAND => 'set sql_mode="TRADITIONAL"'));
      $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
      die ("Connect Failed! :".$e->getMessage());
   }
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   $result = $dbcon->query($sql);
   $useropts='';
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $useropts.='<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
}

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
$sqldata = $dbcon->query($sqlget);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
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
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</o
ption>";}
echo '</select>';

echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";}
echo '</select>';

echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>User:</label>';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   $res = $dbcon->query($sql);
   while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].' </option>';
   }
} else {
   echo $useropts;
}
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
   $user = escapehtml($_POST['user']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);

   $sql = "update comments set comments='".$comSanitized."', username='".$user;
   $sql .= "', comDate='".$year."-".$month."-".$day."' where id=".$id;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo "<meta http-equiv=\"refresh\" content=\"0;URL=notesTable.php?year=".$origYear.'&month='
     .$origMonth.'&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
     "&tab=notes:notes_report&submit=Submit\">";
}
?>
