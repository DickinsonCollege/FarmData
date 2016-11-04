<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>

<center><h2> Input Labor Record</h2></center>

<script type="text/javascript">
function show_confirm() {
	var hid = document.getElementById("numCropsInp");
	hid.value = numCrops;
	console.log("hid: " + hid.value);
	var i = document.getElementById("month");
	var strUser3 = i.options[i.selectedIndex].text;
	var con="Date of Labor: "+strUser3+"-";
	var i = document.getElementById("day");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"-";
	var i = document.getElementById("year");
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+strUser3+"<br>";
	var i = document.getElementById("fieldID");
	if(checkEmpty(i.value)) {
		showError("Please Select a Field Name");
		return false;
	}
	var strUser3 = i.options[i.selectedIndex].text;
	con=con+"Name of Field: "+ strUser3+ "<p>";

	if (numCrops < 1) {
		showError("Select At Least One Crop");
		return false;
	}
	var c = 1;
	var allcrops = [];
	var sum = 0;
	while (c <= numCrops) {
		var crpsel = document.getElementById("crop"+c);
		var crp = document.getElementById("crop"+c).value;
		var perc = document.getElementById("perc"+c).value;
		if (checkEmpty(crp) || crp == "Crop") {
			showError("Please Select a Crop in Box: " + c);
			return false;
		}
		allcrops[c - 1] = crp;
		sum += parseInt(perc);
		con += "Crop: " + crp +"<br>Labor: " + perc + "%<p>";
		c++;
	}
	// con += "<p>";
	if (sum != 100) {
		showError("Percentages Do Not Sum to 100%");
		return false;
	}
	allcrops.sort();
	for (i = 0; i < allcrops.length - 1; i++) {
		if (allcrops[i] == allcrops[i + 1]) {
			showError("Error: same crop entered twice!");
			return false;
		}
	} 

	var i = document.getElementById("task");
	var strUser3 = i.options[i.selectedIndex].text;
	if(checkEmpty(i.value)) {
		showError("Please Select a Task");
		return false;
	}
	con=con+"Task: "+ strUser3+ "<br>";

	var numW = document.getElementById("numW").value;
	if (checkEmpty(numW) || numW<=0 || !isFinite(numW)) {
		showError("Please enter a valid number of workers!");
		return false;
	}
	con=con+"Number of workers: "+ numW+ "<br>";

	var tme = document.getElementById("time").value;
	var unit = document.getElementById("timeUnit").value
	if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
		showError("Please enter a valid number of " + unit + "!");
		return false;
	}
	con=con+"Number of " + unit + ": "+ tme + "<br>";
        var msg = "Confirm Entry:"+"<br>"+con;

	// return confirm("Confirm Entry:"+"<br>"+con);
        showConfirm(msg, 'laborform');
}
</script>

<form name='form' class = 'pure-form pure-form-aligned' id='laborform'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=labor:labor_input" >

<fieldset>

<input type="hidden" name="numCropsInp" id="numCropsInp">
<div class = "pure-control-group">
<label for="crop">Date of Labor:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class = "pure-control-group">
<label for="fieldID">Name of Field:</label>
<select name="fieldID" id="fieldID" onChange="setAllCrops();" class="mobile-select">
<option value="N/A">N/A</option>
<?php
   $sql = 'select fieldID from field_GH where active = 1';
   $result = $dbcon->query($sql);
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      echo '<option value="'.$row['fieldID'].'">'.$row['fieldID'].'</option>';
   }
?>
</select>
</div>
<br clear="all"/>
<table class = "pure-table pure-table-bordered" name="cropTable" id="cropTable">
	<thead><tr><th>Crop</th><th>Percent Of Labor</th></tr></thead>
	<tbody></tbody>
</table>

<script type="text/javascript">
var cropOps = "";

function setCropOps() {
  var fieldID = encodeURIComponent(document.getElementById('fieldID').value);
  var year = document.getElementById('year').value;
  var month = document.getElementById('month').value;
  var day = document.getElementById('day').value;
  var date = year + "-" + month + "-" + day;
  xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET", "update_crop.php?fieldID="+fieldID+"&laborDate="+date, false);
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
  var cell0inner = '<div class="styled-select" id="cropDiv'+ind+'"><select class="wide" name="crop'+ind+'" id = "crop'+ind+'"> ' +
//  '<option value = 0 disabled selected>' +
  'Crop</option> <option value="N/A">N/A</option>' + cropOps + '</select></div>';
  //'<label style="width:2ex"></label>' +
  var cell1inner = '<div class="styled-select" id="percDiv'+ind+'"><select class="wide" name="perc'+ind+'" id = "perc'+ind+'">';
  var p = 5;
  var sel = '';
  while (p <= 100) {
     if (p == 100 && ind == 1) {
        sel = ' selected ';
     }
     cell1inner += '<option ' + sel + ' value="'+p+'">'+p+'%</option>';
     p += 5;
  }
  cell1inner += '</select></div>';
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
  var table = document.getElementById("cropTable").getElementsByTagName('tbody')[0];
  var row 	= table.insertRow(numCrops-1);
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
/*
 function addInput2(){
    var newdiv = document.getElementById('fieldID2');
    var year = document.getElementById("year").value;
    var month = document.getElementById("month").value;
    var day = document.getElementById("day").value;
    var date = year + "-" + month + "-" + day;
    var crp = encodeURIComponent(document.getElementById("cropButton").value);
    xmlhttp= new XMLHttpRequest();
    var cropString = crp;
    if (cropString == "N/A" || cropString == "0") {
        cropString = "%";
    }
    xmlhttp.open("GET", "/Harvest/update_field.php?crop="+cropString+"&harvDate="+date, false);
    var g = document.getElementById("crop");
    xmlhttp.send();
    if(xmlhttp.responseText=="\n" && crp != "N/A") {
       showError("No crops planted in this year");
      cb.value="";
    }
    newdiv.innerHTML="<div class='styled-select' id ='fieldID2'>  <select class='mobile-select' name= 'fieldID' id= 'fieldID'>"
   + "<option value=\"N/A\">N/A</option>"
   +xmlhttp.responseText+"</select> </div>";
//console.log('c'+newdiv.innerHTML+'d');
//console.log('c2'+xmlhttp.responseText+'d2');
}
*/

function addFieldID() {
  setCropOps();
}
 </script>
</fieldset>
<div class = "pure-g">
<div class = "pure-u-1-2">
<input type='button' id='add' class='genericbutton pure-button wide' value="Add Crop" onClick = "addCrop();">
</div>
<div class = "pure-u-1-2">
<input type='button' id='remove' class='genericbutton pure-button wide' value="Remove Crop" onClick = "removeCrop();">
</div>
</div>
<p>
<div class = "pure-control-group">
<label for="taskDiv"> Task:</label>
<select class="mobile-select" name="task" id="task">
<option disabled value = "0" selected>  TASK </option>
<?php
$result=$dbcon->query("Select task from task");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){  
   echo "\n<option value= \"$row1[task]\">".$row1[task]."</option>";
}
?>
</select>
</div>

<div class = "pure-control-group">
<label for="numWorkers">Number of Workers:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; type = "text" value = 1 name="numW" id="numW" class="textbox2 mobile-input">  
</div>

<div class = "pure-control-group">
<label>Enter time in Hours or Minutes:</label>
<input onkeypress= 'stopSubmitOnEnter(event);stopTimer();' type = "text" name="time" id="time" 
   class="textbox2 mobile-input" value="1">  
<select name="timeUnit" id="timeUnit" class="mobile-select" onchange="stopTimer();">
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</div>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/timer.php';
?>

<div class = "pure-control-group">
<label for="comments">Comments:</label>
<textarea  name="comments"rows="10" cols="31">
</textarea>
</div>
<br clear="all"/>
<div class = "pure-g">
<div class = "pure-u-1-2">
<input  class="submitbutton pure-button wide"  type="button"  value="Submit" onclick="show_confirm();">
</div>
</form>
<div class = "pure-u-1-2">
<form method="POST" action = "/Labor/laborReport.php?tab=labor:labor_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>
<br clear = "all"/>
</div>
</div>

<?php
//if(isset($_POST['submit'])){
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
   $sql = "INSERT INTO labor(username,ldate,crop,fieldID,task,hours, comments) VALUES('".
      $user."','".$year.'-'.$month.'-'.$day."', :crop,'".$fieldID."','".
      $task."', :perc, '".$comments."')";
   try {
      $stmt = $dbcon->prepare($sql);
      for ($i=1; $i <= $numCrps; $i++) {
         $crop = escapehtml($_POST['crop'.$i]);
         $perc = escapehtml($_POST['perc'.$i]);
         $perc = ($perc * $totalHours)/100;
         $stmt->bindParam(':crop', $crop, PDO::PARAM_STR);
         $stmt->bindParam(':perc', $perc, PDO::PARAM_INT);
         $stmt->execute();
/*
         $sql = "INSERT INTO labor(username,ldate,crop,fieldID,task,hours, comments) VALUES('".
           $user."','".$year.'-'.$month.'-'.$day."','".$crop."','".$fieldID."','".
           $task."',".(($perc * $totalHours)/100).",'".$comments."')";
*/
      }
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>
