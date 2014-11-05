<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Insect Scouting Input Form </h3>
<br clear="all"/>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_pest:pest_input">
<label for='date'>Date: &nbsp;</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" class="mobile-select">
<option value = 0 selected disabled> FieldID</option>
<?php
$result=mysql_query("Select fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="Pest"> Insect:&nbsp; </label>
<div class="styled-select">
<select name="pest" id="pest" class="mobile-select">
<option  value = 0 selected disabled > Insect </option>
<?php
$result=mysql_query("Select pestName from pest");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[pestName]\">$row1[pestName]</option>";
}
?>
</select>
</div>
<br clear="all"/>

<label for="Crop"> Crop: &nbsp; </label>
<div class="styled-select">
<select name="crop" id="crop" class="mobile-select">
<option value = 0 disabled selected="selected" style="display:none"> Crop </option>
<?php
$result=mysql_query("Select crop from plant");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[crop]\">$row1[crop]</option>";
}
?>
</select>
</div>
<br clear="all"/>
<label for="averages"> Number of Plants Sampled: &nbsp; </label>
<div id="avg" class="styled-select">
<select name ="averages" id="averages" onChange="addBoxes();" class="mobile-select">
<option selected disabled>No. </option>
<?php
$cons=1;
while ($cons<=20) {
	echo '<option value ='.$cons.' >'.$cons.'</option>';
	$cons++;
}
?>
</select>
</div>
<br clear="all"/>
<label for="pest"> Insects Per Plant: </label>
<br clear="all"/>
 <div id="container"></div>
<script type="text/javascript">
var num=0;
var container = document.getElementById('container');
function addBoxes()  {
num=0;
var no=document.getElementById('averages');
//console.log(n1);
var n1=no.options[no.selectedIndex].text;
container.innerHTML="";
while (n1>0) {
	num++;
	var input = document.createElement('input'),
	div = document.createElement('div');
//	div.id="avg"+num;
	input.type = "text";
	input.className="textbox4";
//	input.value=0;
//	input.onCha=calculate();	
	input.id="avg"+num;
//	input.onkeyup=calculate();	
	div.appendChild(input);
	//...
	container.appendChild(div);
	n1--;
//	document.getElementById("avg"+num).className = "textbox4";
};
}
</script>
<script type="text/javascript">
function calculate() {
   var  total=0;
   count=num;
   while (count>0) {
      var e= document.getElementById("avg"+count);
      if (e != null) {
         t= parseFloat(e.value);
         total=total+t;	
      }
      count--;
   }
   document.getElementById('average').value= total/num;
}
</script>
<br clear="all"/>
<input class="genericbutton" type="button" id="Avg"
   value="Calculate Average" onClick="calculate();"/>
<br clear="all"/>
<label for="average">Average Insects Per Plant:&nbsp; </label>
<input  type="text" class ="textbox2 mobile-input" id="average" name="average">
<br clear="all"/>

<br clear="all"/>
<label for="average">Comments:&nbsp; </label>
<br clear="all"/>
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
<script type="text/javascript">
function show_confirm() {
        var i = document.getElementById("fieldID");
	var strUser3 = i.value;
        if(checkEmpty(strUser3)) {
        alert("Please Select a FieldID");
        return false;
        }
        var con="Field ID: "+ strUser3+ "\n";
        var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Scout Date: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"\n";
        var i = document.getElementById("pest");
	var strUser3 = i.value;
	console.log(strUser3);
        if(checkEmpty(strUser3)) {
        alert("Please Select an Insect");
        return false;
        }
        var con=con+"Insect: "+strUser3+"\n";
        var i = document.getElementById("crop");
        if(checkEmpty(i.value)) {
           alert("Please Select a Crop");
           return false;
        }
	var a=num;
	while (a>0) {
		if (document.getElementById('avg'+a).value <0) {
			alert("Enter valid average in box "+a);
			return false;
		}else {
			a--;
		}
	}	
	var strUser3 = i.options[i.selectedIndex].text;
        var con=con+"Crop: "+strUser3+"\n";
        var i = document.getElementById("average").value;
        if(checkEmpty(i) && i != 0) {
           alert("Please Calculate the Average Insects Per Plant");
           return false;
        }
	var con=con+"Average Insects Per Plant: "+ i+ "\n";
        var i = document.getElementById("comments").value;
        var con=con+"Comments: "+ i+ "\n";

	return confirm("Confirm Entry:"+"\n"+con);

}
</script>

<br clear="all"/>
<br clear="all"/>
<input type="submit" class = "submitbutton" value="Submit" name="submit" onClick="return show_confirm();">
</form>

<?php
if (isset($_POST['submit'])) {
   $fieldID = escapehtml($_POST['fieldID']);
   $crop = escapehtml($_POST['crop']);
   $pest = escapehtml($_POST['pest']);
   $average = escapehtml($_POST['average']);
   $comments = escapehtml($_POST['comments']);
   echo "<script> calculate();</script>";
   $sql="Insert into pestScout(sDate,fieldID,crop,pest,avgCount,comments) values ('".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID.
      "','".$crop."','".$pest."','".$average."','".$comments."')";
   $result=mysql_query($sql);
   if (!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>

<form method="POST" action = "pestReport.php?tab=soil:soil_scout:soil_pest:pest_report"><input type="submit" class="submitbutton" value = "View Table">
</form>
