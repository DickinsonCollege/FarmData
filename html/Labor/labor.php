<?php session_start();?>
<!DOCTYPE html>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<label for="harvest"> Input Labor Record </label>

<script type="text/javascript">
function show_confirm() {
	var hid = document.getElementById("numCropsInp");
	hid.value = numCrops;
	console.log("hid: " + hid.value);
	var i = document.getElementById("month");
	var strUser3 = i.options[i.selectedIndex].text;
	var con="Task Date: "+strUser3+"-";
	var i = document.getElementById("day");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"-";
	var i = document.getElementById("year");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"\n";
	var i = document.getElementById("fieldID");
	if(checkEmpty(i.value)) {
		alert("Please Select a FieldID");
		return false;
	}
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+"FieldID: "+ strUser3+ "\n";

	if (numCrops < 1) {
		alert("Select At Least One Crop");
		return false;
	}
	var c = 1;
	var allcrops = [];
	var sum = 0;
	con += "\n";
	while (c <= numCrops) {
		var crpsel = document.getElementById("crop"+c);
		var crp = document.getElementById("crop"+c).value;
		var perc = document.getElementById("perc"+c).value;
		if (checkEmpty(crp) || crp == "Crop") {
			alert("Please Select a Crop in Box: " + c);
			return false;
		}
		allcrops[c - 1] = crp;
		sum += parseInt(perc);
		con += "Crop: " + crp +"\nLabor: " + perc + "%\n\n";
		c++;
	}
	con += "\n";
	if (sum != 100) {
		alert("Percentages Do Not Sum to 100%");
		return false;
	}
	allcrops.sort();
	for (i = 0; i < allcrops.length - 1; i++) {
		if (allcrops[i] == allcrops[i + 1]) {
			alert("Error: same crop entered twice!");
			return false;
		}
	} 

	var i = document.getElementById("task");
	var strUser3 = i.options[i.selectedIndex].text;
	if(checkEmpty(i.value)) {
		alert("Please Select a Task");
		return false;
	}
	con=con+"Task: "+ strUser3+ "\n";

	var numW = document.getElementById("numW").value;
	if (checkEmpty(numW) || numW<=0 || !isFinite(numW)) {
		alert("Enter a valid number of workers!");
		return false;
	}
	con=con+"Number of workers: "+ numW+ "\n";

	var tme = document.getElementById("time").value;
	var unit = document.getElementById("timeUnit").value
	if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
		alert("Enter a valid number of " + unit + "!");
		return false;
	}
	con=con+"Number of " + unit + ": "+ tme + "\n";

	return confirm("Confirm Entry:"+"\n"+con);
}
</script>

<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=labor:labor_input" >

<input type="hidden" name="numCropsInp" id="numCropsInp">
<br clear="all">
<br clear="all">
<label for="crop"><b>Date:</b></label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all">
<label for="fieldID"><b>FieldID:&nbsp</b></label>
<div  id = "fieldID2" class="styled-select">
<select name="fieldID" id="fieldID" onChange="setAllCrops();" class="mobile-select">
<option value=0 selected disabled>FieldID</option>
<option value="N/A">N/A</option>
<?php
   $sql = 'select fieldID from field_GH where active = 1';
   $result = mysql_query($sql);
   echo mysql_error();
   while ($row = mysql_fetch_array($result)) {
      echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].'</option>';
   }
?>
</select>
</div>
<br clear="all">
<!--
<div name = "showCrops" id = "showCrops">
<label>Crop and Percent of Labor</label>
-->
<br clear="all"/>
</div>

<?php
//$labor = true;
//include $_SERVER['DOCUMENT_ROOT'].'/chooseCrop.php';
?>
<table name="cropTable" id="cropTable">
	<tr><th>Crop</th><th>Percent Of Labor</th></tr>
</table>
<br clear="all">

<script type="text/javascript">
var cropOps = "";

function setCropOps() {
  var fieldID = encodeURIComponent(document.getElementById('fieldID').value);
  var year = document.getElementById('year').value;
  xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET", "update_crop.php?fieldID="+fieldID+"&plantyear="+year, false);
  xmlhttp.send();
  cropOps = xmlhttp.responseText;
}
 
function setCrop(ind) {
// pre: setCropOps is called first
  var table = document.getElementById("cropTable");
  var row = table.rows[ind];
  var cell0 = row.cells[0];
  var cell1 = row.cells[1];
  //var cropdiv = document.getElementById('cropDiv'+ind);
  var cell0inner = '<div class="styled-select" id="cropDiv'+ind+'"><select class="mobile-select" name="crop'+ind+'" id = "crop'+ind+'"> ' +
  '<option value = 0 disabled selected>' +
  'Crop</option> <option value="N/A">N/A</option>' + cropOps + '</select></div>';
  //'<label style="width:2ex"></label>' +
  var cell1inner = '<div class="styled-select" id="percDiv'+ind+'"><select class="mobile-select" name="perc'+ind+'" id = "perc'+ind+'">';
  var p = 5;
  var sel = '';
  while (p <= 100) {
     if (p == 100 && ind == 1) {
        sel = ' selected ';
     }
     cell1inner += '<option ' + sel + ' value="'+p+'">'+p+'%</option>';
     p += 5;
  }
  cell1inner += '</select></div> <br clear="all"/>';
  cell0.innerHTML =  cell0inner;
  cell1.innerHTML =  cell1inner;
}

var numCrops = 0;

function setAllCrops() {
  setCropOps();
  var crp = 1;
  while (crp <= numCrops) {
     setCrop(crp);
     crp++;
  }
}

function addCrop() {
  numCrops++;
  var table = document.getElementById("cropTable");
  var row 	= table.insertRow(numCrops);
  row.id		= "row" + numCrops;
  row.name	= "row" + numCrops;
  row.insertCell(0);
  row.insertCell(1);
  //var newCrop = document.createElement('div');
  //newCrop.className = "styled-select";
  //newCrop.name = "cropDiv" + numCrops;
  //newCrop.id = "cropDiv" + numCrops;
  //div.appendChild(newCrop);
  setCrop(numCrops);
}

function removeCrop() {
  if (numCrops >0) {
  	var crop = document.getElementById("crop"+numCrops);
  	//var cropDiv = document.getElementById("cropDiv" + numCrops);
   //cropDiv.removeChild(crop);
   	crop.parentNode.removeChild(crop);
  	var perc = document.getElementById("perc"+numCrops);
  	//var percDiv = document.getElementById("percDiv"+numCrops);
	//percDiv.removeChild(perc);
	perc.parentNode.removeChild(perc);
  	var table = document.getElementById("cropTable");
	table.deleteRow(numCrops);
	numCrops--;
  }
}

setCropOps();
addCrop();

 function addInput() {}
 function addInput2(){
    var newdiv = document.getElementById('fieldID2');
    var f = document.getElementById("year");
    var crp = encodeURIComponent(document.getElementById("cropButton").value);
    var strUser2 = f.options[f.selectedIndex].text;
    xmlhttp= new XMLHttpRequest();
    var cropString = crp;
    if (cropString == "N/A" || cropString == "0") {
        cropString = "%";
    }
    xmlhttp.open("GET", "/Harvest/update_field.php?crop="+cropString+"&plantyear="+strUser2, false);
    var g = document.getElementById("crop");
    xmlhttp.send();
    if(xmlhttp.responseText=="\n" && crp != "N/A") {
       alert("No crops planted in this year");
      cb.value="";
    }
    newdiv.innerHTML="<div class='styled-select' id ='fieldID2'>  <select class='mobile-select' name= 'fieldID' id= 'fieldID'>"
   + "<option value=\"N/A\">N/A</option>"
   +xmlhttp.responseText+"</select> </div>";
//console.log('c'+newdiv.innerHTML+'d');
//console.log('c2'+xmlhttp.responseText+'d2');
}
 </script>
<?php
if($_SESSION['mobile']){
 echo "<br clear=\"all\">";
}
?>

<input type='button' id='add' class='genericbutton' value="Add Crop" onClick = "addCrop();">

<!--
<label style="width:2ex;float:left"></label>
-->

<input type='button' id='remove' class='genericbutton' value="Remove Crop" onClick = "removeCrop();">
<br clear="all"/>
<br clear="all"/>

<label for="taskDiv"><b> Task:&nbsp; </b></label>

<div class = "styled-select" id= "taskDiv">
<select class="mobile-select" name="task" id="task">
<option disabled value = "0" selected>  TASK </option>
<?php
$result=mysql_query("Select task from task");
while ($row1 =  mysql_fetch_array($result)){  
   echo "\n<option value= \"$row1[task]\">".$row1[task]."</option>";
}
?>
</select>
</div>

<br clear="all"/>
<label for="numWorkers"><b>Number of workers:&nbsp;</b></label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input">  
<br clear="all"/>
<br clear="all"/>

<label>Enter time in Hours or Minutes:</label>
<br clear="all"/>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" name="time" id="time" class="textbox2 mobile-input">  
<div class="styled-select">
<select name="timeUnit" id="timeUnit" class="mobile-select">
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div>
<br clear="all"/>
<div>
<label for="comments">Comments:</label>
<br clear="all"/>
<textarea  name="comments"rows="20" cols="30">
</textarea>
</div>
<br clear="all"/>

<input  class="submitbutton"  type="submit" name="submit" value="Submit" onclick= "return show_confirm();">

</form>
<br clear="all"/>
<form method="POST" action = "/Labor/laborReport.php?tab=labor:labor_report"><input type="submit" class="submitbutton" value = "View Table"></form>

<?php
if(isset($_POST['submit'])){
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $fieldID = escapehtml($_POST['fieldID']);
//   $crop = escapehtml($_POST['crop']);
   $task = escapehtml($_POST['task']);
  
   // Check if given time is in minutes or hours
   $time = escapehtml($_POST['time']);
   if ($_POST['timeUnit'] == "minutes") {
      $hours = $time/60;
   } else if ($_POST['timeUnit'] == "hours") {
      $hours = $time;
   }
   // Check if num workers is filled in
   $numW = escapehtml($_POST['numW']);
   if ($numW != "") {
      $totalHours = $hours * $numW;
   } else {
      $totalHours = $hours;
   }

   $comments = escapehtml( $_POST['comments']);
   $user =escapehtml( $_SESSION['username']);
   $numCrps = $_POST['numCropsInp'];
   $success = true;
   for ($i=1; $i <= $numCrps; $i++) {
      $crop = escapehtml($_POST['crop'.$i]);
      $perc = escapehtml($_POST['perc'.$i]);
      $sql = "INSERT INTO labor(username,ldate,crop,fieldID,task,hours, comments) VALUES('".
        $user."','".$year.'-'.$month.'-'.$day."','".$crop."','".$fieldID."','".
        $task."',".(($perc * $totalHours)/100).",'".$comments."')";
      $value = mysql_query($sql);
      if(!$value){
          echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
          $success = false;
      }
   }
   if ($success) {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>
