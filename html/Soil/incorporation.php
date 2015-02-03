<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3 class="hi"> Cover Crop Incorporation </h3>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_input">
<br clear="all"/>
<label for='date'>Date:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" onchange="selectDates(); selectSpecies();" class='mobile-select'>
<option value = 0 selected disabled> FieldID</option>
<?php
$sqlget = "select distinct fieldID from coverSeed_master";
$result=mysql_query($sqlget);
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>

<br clear='all'/>
<label for='sdate'> Seed Date: </label>
<div class='styled-select' id='seeddateDiv'>
<select name='seeddate' id='seeddate' onchange="selectSpecies();" class='mobile-select'>
<option value=0 selected disabled>Seed Date</option>
</select></div>
<input type='hidden' id='numCrops' name='numCrops' value=0>

<script type='text/javascript'>
function selectDates() {
	var xmlhttp = new XMLHttpRequest();
	var fieldID = encodeURIComponent(document.getElementById('fieldID').value);

	xmlhttp.open("GET", "update_date.php?fieldID=" + fieldID, false);
	xmlhttp.send();

	var seeddateDiv = document.getElementById('seeddateDiv');
	seeddateDiv.innerHTML = "<select name='seeddate' id='seeddate' class='mobile-select' onchange='selectSpecies();'>" + xmlhttp.responseText + "</select>";
}

function selectSpecies() {
	var xmlhttp = new XMLHttpRequest();
	var fieldID = encodeURIComponent(document.getElementById('fieldID').value);
	var b = document.getElementById('seeddate');
	var seedDate = b.options[b.selectedIndex].text;

	xmlhttp.open("GET", "update_species.php?fieldID=" + fieldID + "&seedDate=" + seedDate, false);
	xmlhttp.send();

	var table = document.getElementById('coverCropTable');
	table.innerHTML = "<tr><th>Species</th></tr>";
	var speciesNames = eval(xmlhttp.responseText);

	var count = 0;
	for (i = 0; i < speciesNames.length; i++) {
		count++;
		var row = table.insertRow(-1);
		var cell = row.insertCell(0);
		cell.innerHTML = "<input readonly type='text' style='width:100%;' class='textbox25 mobile-input single_table'" + 
			"name='crop" + count + "' id='crop" + count + "' value='" + speciesNames[i] + "'>";
	}

	var hi = document.getElementById('numCrops');
	hi.value = count;
}
</script>

<br clear='all'>
<br clear="all"/>
<table id='coverCropTable' name='coverCropTable'>
	<tr><th>Species</th></tr>
</table>
<?php if($_SESSION['mobile']) echo "<div style='margin-top: 100px;'>"; ?>

<br clear='all'>
<br clear='all'>

<input onclick="addMeasurement();" class="genericbutton" style="float:left;" 
  type="button" id="add" value="Add Biomass Calculation"/>
<br clear="all"/>
<label for="pounds">&nbsp;Lbs Per 4 Sq. Ft. </label>
<br clear="all"/>
<div id="container"></div>
<br clear="all"/>
<input type="button" id="remove" class = "genericbutton"
  value="Remove Biomass Calculation" onClick="remov();"/>
<br clear="all"/>
<br clear="all"/>
<script type="text/javascript">
var num=0;
function remov() {
	var elem = document.getElementById("container");
	console.log(elem);
	var elem2 = document.getElementById("div"+num);
	elem.removeChild(elem2);
	num--;
}
</script>


<input type="button" id="Avg" value="Calculate" class="genericbutton" onClick="calculate(); calculate2();"/>
<br clear="all"/>
<?php
if($_SESSION['mobile']){
   echo '<label for="average"> Biomass Pounds Per Acre:</label>';
   echo '<br clear="all"/>';
}else {
echo '<label for="average"> Biomass Pounds Per Acre: &nbsp; </label>';
}
?>
<input type="text" value=0 readonly id="average" class="textbox25 mobile-input" name="average" >
<br clear="all"/>
<?php 
if($_SESSION['mobile']){
   echo '<label for="average"> Total Biomass Pounds:</label>';
   echo '<br clear="all"/>';
} else {
echo '<label for="average"> Total Biomass Pounds: &nbsp; </label>';
}
?>
<input type="text" value=0 readonly disabled id="total89" class="textbox25 mobile-input" name="total89" >
<br clear="all"/>
<label for"incorp20"> Incorporation Tool: &nbsp; </label>
<div class="styled-select">
<select id="tool" name="tool" class='mobile-select'>
<option value = 0 selected disabled> Incorporation Tool</option>
<?php
$result=mysql_query("Select tool_name from tools where type='INCORPORATION'"); 
while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
 }
?>
</select>
</div>
<br clear="all">

<label for="tractor"> Tractor: </label>
<div class="styled-select" id="tractor2">
   <select name="tractor" id="tractor" class='mobile-select'>
      <option value=0 selected disabled> Tractor </option>
      <?php
      $result = mysql_query("Select tractorName from tractor where active = 1");
      while ($row = mysql_fetch_array($result)) {
         echo "\n<option value=\"$row[tractorName]\">$row[tractorName]</option>";
      }
      echo '</select>';
      echo '</div>';
      ?>
   </select>
</div>
<br clear="all">

<label for="numPasses"> Number of Passes: </label>
<div class="styled-select" id="numPasses2">
   <select name="numPasses" id="numPasses" class='mobile-select'>
      <option value=0 selected disabled> Passes </option>
      <?php
      for ($i = 1; $i <= 5; $i++) {
         echo "\n<option value=".$i.">".$i."</option>";
      }
      echo '</select>';
      echo '</div>';
      ?>
   </select>
</div>
<br clear="all">

<label for="percTilled"> % of Field Tilled: </label>
<div class="styled-select" id="percTilled2">
   <select name="percTilled" id="percTilled" class='mobile-select'>
      <option value=0 selected disabled> % Tilled </option>
      <?php
      for ($i = 10; $i <= 100; $i += 10) {
         echo "\n<option value=".$i.">".$i."%</option>";
      }
      echo '</select>';
      echo '</div>';
      ?>  
   </select>
</div>
<br clear="all">

<label for="minutes"> Minutes in Field: </label>
<div class="styled-select" id="minutes2">
   <select name="minutes" id="minutes" class='mobile-select'>
      <option value=0 selected disabled> Minutes </option>
      <?php
      for ($i = 1; $i <= 300; $i++) {
         echo "\n<option value=".$i.">".$i."</option>";
      }
      echo '</select>';
      echo '</div>';
      ?>
   </select>
</div>
<br clear="all">


<script type="text/javascript">
var container = document.getElementById('container');
function addMeasurement() {
num++;
        var input = document.createElement('input'),
        div = document.createElement('div');
//      div.id="avg"+num;
        input.type = "text";
        input.className="textbox4 mobile-input-half";
        input.value=0;
//      input.onCha=calculate();        
        input.id="avg"+num;
        div.id="div"+num;
        div.appendChild(input);
        //...
        container.appendChild(div);
};

/*
function addSeedDate(seedDate, seedID, both) {
        console.log(seedDate+"seedDateID");
	var newdiv = document.getElementById(seedDate);
        var e = document.getElementById(both);
        var f = document.getElementById("fieldID");
        console.log(f.value+"fieldID");
        console.log(e.value+"cover crop");
        var strUser= e.value;
        var strUser2= f.value;
        xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET", "update_SeedDate.php?both="+strUser+"&fieldID="+strUser2, false);
        xmlhttp.send();
        newdiv.innerHTML="<div class='styled-select' id ='"+seedDate+"'>  <select name= '"+seedID+"' id= '"+seedID+"' class='mobile-select'>"+xmlhttp.responseText+"</select> </div>";
        if (xmlhttp.responseText == "\n" && f.value != "0" && e.value != "0") {
           alert("No " + e.value + " seeded in Field: " + f.value + "!");
        }
}
*/
function calculate() {
var  total=0;
count=num;
        while (count>0) {
                var t=parseFloat(document.getElementById("avg"+count).value,10);
console.log(t);
                total=total+t;  
        console.log(total);
                count--;
        }
//console.log(total/count);
console.log(total+"total");
console.log(count+"count");
document.getElementById('average').value= ((total/num)*10890).toFixed(2);;
return document.getElementById('average').value;
}

function calculate2() {     
        var newdiv = document.getElementById('total89');
	var fieldID = encodeURIComponent(document.getElementById('fieldID').value);
        var sdate = document.getElementById("seeddate").value;
	xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET", "update_total.php?fieldID="+fieldID+"&seeddate="+
          sdate, false);
        xmlhttp.send();
console.log(xmlhttp.responseText+"responsetext");
        var total = parseFloat(xmlhttp.responseText,10)*calculate();
document.getElementById('total89').value= total.toFixed(2);;
}

function show_confirm() {
	
	var fld = document.getElementById("fieldID").value;
	if(checkEmpty(fld)) {
	   alert("Please Enter Field");
	   return false;
	}
        var con="Field ID: "+ fld+ "\n";
	var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        con=con+"Kill Date: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        con=con+strUser3+"\nCrop(s):\n";
	var num = document.getElementById('numCrops').value;
        if (num < 1) {
           alert("No crops seeded!");
           return false;
        }
        for (i = 1; i <= num; i++) {
          con+= document.getElementById("crop"+i).value + "\n";
        }
        
/*
	var strUser3 = i.options[i.selectedIndex].text;
        con=con+"Crop Species: "+strUser3+"\n";
	var i = document.getElementById("seeddate");
	var strUser3 = i.options[i.selectedIndex].text;
        con=con+"Seed Date: "+strUser3+"\n";
        var i = document.getElementById("both2");
        if (!checkEmpty(i.value)) {
           con=con+"Second Crop Species: "+i.value+"\n";
	   var i = document.getElementById("seeddate2").value;
           con=con+"Second Species Seed Date: "+i+"\n";
        }
*/
	var i = document.getElementById("average").value;
	if(checkEmpty(i)) {
	   alert("Please Calculate Biomass Pounds Per Acre by Clicking Calculate");
	   return false;
	}
        con=con+"Biomass Pounds Per Acre: "+ i+ "\n";
	var i = document.getElementById("total89").value;
	if(checkEmpty(i)) {
	   alert("Please Calculate Total Biomass Pounds by Clicking Calculate");
	   return false;
	}
        con=con+"Total Biomass Pounds: "+ i+ "\n";
	var i = document.getElementById("tractor").value;
	if(checkEmpty(i)) {
	   alert("Please Select a Tractor");
	   return false;
	}
        con=con+"Tractor: "+ i+ "\n";
	var i = document.getElementById("tool").value;
	if(checkEmpty(i)) {
	   alert("Please Select a Tool");
	   return false;
	}
        con=con+"Incorporation Tool: "+ i+ "\n";
	var i = document.getElementById("numPasses").value;
	if(checkEmpty(i)) {
	   alert("Please Select Number of Passes");
	   return false;
	}
        con=con+"Number of Passes: "+ i+ "\n";
	var i = document.getElementById("percTilled").value;
	if(checkEmpty(i)) {
	   alert("Please Select Percent of Field Tilled");
	   return false;
	}
        con=con+"Percent of Field Tilled: "+ i+ "\n";
	var i = document.getElementById("minutes").value;
	if(checkEmpty(i)) {
	   alert("Please Select Minutes in Field");
	   return false;
	}
        con=con+"Minutes in Field: "+ i+ "\n";
	var i = document.getElementById("comments").value;
        con=con+"Comments: "+ i+ "\n";
	return confirm("Confirm Entry:"+"\n"+con);

}
</script>


<label for="comments"> Comments: </label>
<br clear="all"/>
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
<br clear="all"/>
<br clear="all"/>
<input type="submit" value="Submit" name="submit" class="submitbutton" onclick="return show_confirm();">
</form>
<form method="POST" action = "incorpReport.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report"><input type="submit" class="submitbutton" value = "View Table"></form>
<?php
if (isset($_POST['submit'])) {
   $acre=43560;
   $avg=4;
   $fieldID = escapehtml($_POST['fieldID']);
   $seedDate = escapehtml($_POST['seeddate']);
   $incorpTool = escapehtml($_POST['tool']);
   $aver = escapehtml($_POST['average']);
   $comments = escapehtml($_POST['comments']);
   echo "<script>calculate();</script>";

	// Insert into coverKill_master
	$sql = "INSERT INTO coverKill_master(killDate, incorpTool, totalBiomass, comments, fieldID, seedDate) 
		VALUES('".$_POST['year']."-".$_POST['month']."-".$_POST['day']."', '".$incorpTool."', 
			(".$aver.")*(SELECT area_seeded FROM coverSeed_master 
			WHERE fieldID='".$fieldID."' AND seedDate='".$seedDate."')
			/100*(SELECT size FROM field_GH WHERE fieldID='".$fieldID."'), 
		'".$comments."', '".$fieldID."', '".$seedDate."')";
	$result1 = mysql_query($sql);

	// Inert into coverKill
	$count = 1;
	$id = mysql_insert_id();
	$numCrops = $_POST['numCrops'];
	while ($count <= $numCrops) {
		$crop = escapehtml($_POST['crop'.$count]);
		$sql = "INSERT INTO coverKill(coverCrop, id) 
			VALUES ('".$crop."', ".$id.");";
		$result = mysql_query($sql);
		echo mysql_error();
		$count++;
	}

   // Insert into tillage
   $tractor = escapehtml($_POST['tractor']);
   $numPasses = escapehtml($_POST['numPasses']);
   $percTilled = escapehtml($_POST['percTilled']);
   $minutes = escapehtml($_POST['minutes']);
   $sql = "INSERT into tillage(tractorName, fieldID, tilldate, tool, num_passes, comment, minutes, percent_filled)
	values
	('".$tractor."', '".$fieldID."', '".$_POST['year']."-".$_POST['month']."-".$_POST['day']."',
	'".$incorpTool."', ".$numPasses.", '".$comments."', ".$minutes.", ".$percTilled.");";
   $result2=mysql_query($sql);
   echo mysql_error();

   if ($result1 && $result2) {
      echo '<script> showAlert("Cover Crop Incorporation Record Entered Successfully") </script>';
   } else {
      echo '<script> alert("Could not enter data! Check input and try again.\n '.mysql_error().'") </script>';
   } 
}
?>
