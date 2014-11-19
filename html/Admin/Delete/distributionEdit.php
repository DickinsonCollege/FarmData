<?php session_start();?>
<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">
<script type="text/javascript">
function getUnit(){
   var newdiv = document.getElementById('unitDiv');
   var crp = encodeURIComponent(document.getElementById("crop").value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "getDefUnit.php?crop="+crp, false);
   xmlhttp.send();
   newdiv.innerHTML= '<div id="unitDiv"> <input type="text" class="textbox25" readonly name="unit" ' +
      ' id="unit" value="' + xmlhttp.responseText + '"></div>';
}
</script>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$id=$_GET['id'];
$origYear	= $_GET['year'];
$origMonth 	= $_GET['month'];
$origDay 	= $_GET['day'];
$origCrop 	= $_GET['crop_product'];
$origTarget = $_GET['target'];
$origGrade	= $_GET['grade'];
$tcurYear 	= $_GET['tyear'];
$tcurMonth 	= $_GET['tmonth'];
$tcurDay 	= $_GET['tday'];

$sqlget = "SELECT id,year(distDate) as yr, month(distDate) as mth, day(distDate) as dy, crop_product, grade, amount,".
			 "unit, comments, target FROM distribution where id = ".$id;
$sqldata = mysql_query($sqlget) or die(mysql_error());
$row = mysql_fetch_array($sqldata);
$unit = $row['unit'];
$grade = $row['grade'];
$amount = $row['amount'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$curCrop = $row['crop_product'];
$comments = $row['comments'];
$target = $row['target'];
$dMonth = $curMonth;
$dYear  = $curYear;
$dDay	  = $curDay;
echo "<form class='pure-form pure-form-aligned' name='form' method='post' action=\"".$_SERVER['PHP_SELF'].
   "?tab=admin:admin_delete:deletesales:delete_dist&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay."&crop_product=".
    encodeURIComponent($origCrop)."&target=".encodeURIComponent($origTarget)."&grade=".encodeURIComponent($origGrade)."&id=".$id."\">";
/*
echo '<input type="hidden" name="oldCrop" value="'.$curCrop.'">';
echo '<input type="hidden" name="oldField" value="'.$grade.'">';
*/
echo '<div class="pure-controls">';
echo "<H3> Edit Distribution Record </H3>";
echo '</div>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo "<div class='pure-control-group'>";
echo "<label for='from'>Date:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo"</div>";
echo '<br clear="all"/>';
echo '<div class="pure-control-group">';
echo '<label>Crop/Product:&nbsp</label>';
echo '<div class="styled-select"><select name="crop" id="crop" onchange="getUnit();">';
echo '<option value="'.$curCrop.'" selected>'.$curCrop.' </option>';
$sql = 'select crop from plant';
$sqldata = mysql_query($sql) or die("ERROR2");
while ($row = mysql_fetch_array($sqldata)) {
   echo '<option value="'.$row['crop'].'">'.$row['crop'].' </option>';
}
echo '</select></div></div>';
echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Grade:&nbsp</label>';
echo '<div class="styled-select"><select name="grade" id="grade" class="mobile-select">';
echo '<option value="'.$grade.'" selected>'.$grade.' </option>';
echo '<option value="1">1</option>';
echo '<option value="2">2</option>';
echo '<option value="3">3</option>';
echo '<option value="4">4</option>';
echo '</select></div></div>';
echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Amount:&nbsp</label>';
echo '<input type="text" class="textbox2" name="amount" id="amount" value="'.$amount.'"></div>';
echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Unit:&nbsp</label>';
echo '<div id="unitDiv"><input type="text" class="textbox25" readonly name="unit" id="unit" value="'.
   $unit.'"></div></div>';
/*
echo '<div class="styled-select"><select name="unit" id="unit">';
echo '<option value="'.$unit.'" selected>'.$unit.' </option>';
$sql = 'select distinct unit from units where crop =\''.$curCrop.'\'';
$sqldata = mysql_query($sql) or die("ERROR4");
while ($row = mysql_fetch_array($sqldata)) {
	echo '<option value="'.$row['unit'].'">'.$row['unit'].' </option>';
}
echo '</select></div></div>';
*/
echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Target:&nbsp</label>';
echo '<div class="styled-select"><select name="target" id="target">';
echo '<option value="'.$target.'" selected>'.$target.' </option>';
$sql = "select distinct targetName from targets where targetName <> '".$target."'";
$sqldata = mysql_query($sql) or die("ERROR4");
while ($row = mysql_fetch_array($sqldata)) {
	echo '<option value="'.$row['targetName'].'">'.$row['targetName'].' </option>';
}
echo '</select></div></div>';
echo '<br clear="all"/>';

echo '<div class="pure-control-group">';
echo '<label>Comments:&nbsp</label>';
echo "<textarea rows=\"10\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea></div>";
echo '<div class="pure-controls">';
echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton'></div>";
echo "</form>";

if ($_POST['submit']) {
   $comSanitized=escapehtml($_POST['comments']);
   $amount = escapehtml($_POST['amount']);
   $bringback = escapehtml($_POST['bringback']);
   $updateGrade = escapehtml($_POST['grade']);
   $crop = escapehtml($_POST['crop']);

	$target = escapehtml($_POST['target']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $unit = escapehtml($_POST['unit']);
   $sql = "update distribution set unit='".$unit."', grade=".$updateGrade.", distDate='".$year."-".
     $month."-".$day."', amount=".$amount.",target='".$target."', comments='".
     $comSanitized."',crop_product='".$crop."' where id=".$id;
   echo $sql;
	$result = mysql_query($sql);
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {

      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      echo "<meta http-equiv=\"refresh\" content=\"0;URL=distributionTable.php?year=".$origYear."&month=".$origMonth.
        		"&day=".$origDay."&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
				"&target=".encodeURIComponent($origTarget)."&grade=".encodeURIComponent($origGrade).
        		"&tab=admin:admin_delete:deletesales:delete_dist".
        		"&crop_product=".encodeURIComponent($origCrop)."&submit=Submit\">";
   }
}
?>
