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

$sqlget = "SELECT id, year(transdate) as tyr, month(transdate) as tmth, day(transdate) as tdy, crop, username, 
   year(seedDate) as syr, month(seedDate) as smth, day(seedDate) as sdy,
   transdate, seedDate, fieldID, bedft, rowsBed, hours, flats, comments FROM transferred_to WHERE id = ".$id;

$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$id = $row['id'];
$seedDay = $row['sdy'];
$seedMonth = $row['smth'];
$seedYear = $row['syr'];
$transDay = $row['tdy'];
$transMonth = $row['tmth'];
$transYear = $row['tyr'];

$curCrop = $row['crop'];
$user = $row['username'];
$transdate = $row['transdate'];
$seedDate = $row['seedDate'];
$fieldID = $row['fieldID'];
$bedft = $row['bedft'];
$rowsBed = $row['rowsBed'];
$hours = $row['hours'];
$flats = $row['flats'];
$comments = $row['comments'];
?>

<script type='text/javascript'>
function updateSeedDate() {
   var crop = encodeURIComponent(document.getElementById('crop').value);
   console.log(crop);

   var xmlhttp = new XMLHttpRequest();
   xmlhttp.open("GET", "/Seeding/update_trans.php?crop="+crop, false);
   xmlhttp.send();
   
        var cur = "";
        if (crop == "<?php echo $curCrop;?>") {
           var dt = "<?php echo $seedDate;?>";
           if (dt != '0000-00-00') {
              cur = '<option value="'+dt+'">'+dt+'</option>';
           }
        }
   var seedDates = document.getElementById('seedDateDiv');
console.log(xmlhttp.responseText);
   seedDates.innerHTML = "<div class='styled-select' id='seedDateDiv'>" + 
      "<select id='seedDate' name='seedDate'>" + cur +
      xmlhttp.responseText + 
      "</select></div>";
} 
window.onload=function(){updateSeedDate();};
</script>

<?php
echo "<form name='form' method='post' action=\"".$SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deleteseed:deleteflats&year=".$origYear."&month=".
   $origMonth."&day=".$origDay.  "&tyear=".$tcurYear."&tmonth=".$tcurMonth.
   "&tday=".$tcurDay."&crop=".encodeURIComponent($origCrop)."&id=".$id."\">";

echo "<H3> Edit Transplant Seeding Report </H3>";
echo '<br clear="all"/>';

echo "<label>Date Transplanted:&nbsp</label>";
echo "<div class='styled-select'><select name='transMonth' id='transMonth'>";
echo '<option value='.$transMonth.' selected>'.date("F", mktime(0,0,0, $transMonth,10)).' </option';
   for ($mth = 1; $mth <= 12; $mth++) {
      echo "\n <option value='$mth'>".date("F", mktime(0,0,0, $mth, 10))."</option>";
   }
echo "</div></select>";
echo "<div class='styled-select'><select name='transDay' id='transDay'>";
echo "<option value='".$transDay."' selected>".$transDay."</option>";
   for ($day = $transDay - $transDay+1; $day <= 31; $day++) {
      echo "\n <option value='".$day."'>".$day."</option>";
   }
echo "</div></select>";
echo "<div class='styled-select'><select name='transYear' id='transYear'>";
echo "<option value='".$transYear."' selected>".$transYear."</option>";
   for ($yr = $transYear - 4; $yr < $transYear + 5; $yr++) {
      echo "\n <option value='".$yr."'>".$yr."</option>";
   }
echo "</div></select>";
echo "<br clear='all'>";

echo '<label>Crop:&nbsp</label>';
echo '<div class="styled-select"><select name="crop" id="crop" onchange="updateSeedDate();">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select distinct crop from gh_seeding WHERE year(seedDate)=year(now())';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</div></select>';
echo '<br clear="all"/>';

echo "<label>Date Seeded:&nbsp</label>";
echo "<div class='styled-select' id='seedDateDiv'><select name='seedDate' id='seedDate'>";
echo "<option value='".$seedDate."' selected>".$seedDate."</option>";
echo '</select></div>';
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

echo "<label>Field ID:&nbsp</label>";
echo "<div class='styled-select'><select name='fieldID' id='fieldID'>";
echo "<option value='".$fieldID."' selected>".$fieldID."</option>";
$sql = 'select fieldID from field_GH where active = 1';
$sqldata = mysql_query($sql) or die();
while ($row = mysql_fetch_array($sqldata)) {
   echo "<option value='".$row['fieldID']."'>".$row['fieldID']."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

echo "<label>Bed Feet Planted:&nbsp</label>";
echo "<input type='text' class='textbox2' name='bedfeet' id='bedfeet' value='".$bedft."'>";
echo "<br clear='all'>";

echo "<label>Rows per Bed:&nbsp</label>";
echo '<div class="styled-select"><select name="rowsbed" id="rowsbed">';
echo '<option value='.$rowsBed.' selected>'.$rowsBed.' </option>';
for ($row = 1; $row <= 7; $row++) {
   echo '<option value='.$row.'>'.$row.'</option>';
}
echo '</div></select>';

// echo "<input type='text' class='textbox2' name='rowsbed' id='rowsbed' value='".$rowsBed."'>";
echo "<br clear='all'>";

echo "<label>Number of Flats:&nbsp</label>";
echo "<input type='text' class='textbox2' name='flats' id='flats' value='".$flats."'>";
echo "<br clear='all'>";

if ($_SESSION['labor']) {
   echo "<label>Hours Worked:&nbsp</label>";
   echo "<input type='text' class='textbox2' name='hours' id='hours' value='".$hours."'>";
   echo "<br clear='all'>";
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
   $username = escapehtml($_POST['user']);
   $comments = escapehtml($_POST['comments']);
   $crop = escapehtml($_POST['crop']);
   $fieldID = escapehtml($_POST['fieldID']);
   $flats = escapehtml($_POST['flats']);
   $bedft = escapehtml($_POST['bedfeet']);
   $rowsbed = escapehtml($_POST['rowsbed']);
   if ($_SESSION['labor']) {
      $hours = escapehtml($_POST['hours']);
   }
   $seedDate = escapehtml($_POST['seedDate']);
   $transYear = escapehtml($_POST['transYear']);
   $transMonth = escapehtml($_POST['transMonth']);
   $transDay = escapehtml($_POST['transDay']);  
 
   $sql = "update transferred_to set username='".$username."',crop='".$crop."', seedDate='".$seedDate."', 
      transdate='".$transYear."-".$transMonth."-".$transDay."', 
      flats='".$flats."', bedft='".$bedft."', rowsBed='".$rowsbed."', hours='".$hours."',
      comments='".$comments."', fieldID='".$fieldID."' WHERE id=".$id;

   $result = mysql_query($sql);
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=transTable.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay."&crop=".encodeURIComponent($_GET['crop']).
        "&tab=admin:admin_delete:deleteseed:deletetrans&submit=Submit\">";
   }
}

?>
