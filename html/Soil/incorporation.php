<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<center>
<h2 class="hi"> Cover Crop Incorporation </h2>
</center>
<form name='form' class='pure-form pure-form-aligned' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_input">
<fieldset>
<div class="pure-control-group">
<label for='date'>Date: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class="pure-control-group" id="field">
<label for="fieldID"> Field ID: </label>
<select name ="fieldID" id="fieldID" onchange="selectDates(); selectSpecies();calculate2();" class='mobile-select'>
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

<div class="pure-control-group" id="seeddateDiv">
<label for='sdate'> Seed Date: </label>
<select name='seeddate' id='seeddate' onchange="selectSpecies();calculate2();" class='mobile-select'>
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
	seeddateDiv.innerHTML = '<div class="pure-control-group" id="seeddateDiv">' +
           "<label for='sdate'> Seed Date: </label> " + 
           "<select name='seeddate' id='seeddate' onchange='selectSpecies();calculate2();' >" +
           xmlhttp.responseText + "</select></div>";
}

function selectSpecies() {
	var xmlhttp = new XMLHttpRequest();
	var fieldID = encodeURIComponent(document.getElementById('fieldID').value);
	var b = document.getElementById('seeddate');
	var seedDate = b.options[b.selectedIndex].text;

	xmlhttp.open("GET", "update_species.php?fieldID=" + fieldID + "&seedDate=" + seedDate, false);
	xmlhttp.send();
	var speciesNames = eval(xmlhttp.responseText);

        var sDiv = document.getElementById("speciesList");
        var content = '<div class="pure-control-group" id="speciesList"><label>Species:</label> ' +
           '<textarea readonly id="listArea" name="listArea">';
	for (i = 0; i < speciesNames.length; i++) {
           content += speciesNames[i];
           if (i < speciesNames.length - 1) {
              content += "\n";
           }
        }
        content += '</textarea> </div>';
        sDiv.innerHTML = content;

	var hi = document.getElementById('numCrops');
	hi.value = speciesNames.length;
}
</script>

<div class="pure-control-group" id="speciesList">
<label>Species:</label>
<textarea readonly id="listArea" name="listArea"></textarea>
</div>

<br clear='all'>

<!--
<br clear="all"/>
<label for="pounds">&nbsp;Lbs Per 4 Sq. Ft. </label>
<br clear="all"/>
-->
<div id="container"></div>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input onclick="addMeasurement();" class="genericbutton pure-button wide" 
  type="button" id="add" value="Add Biomass Calculation"/>
</div>
<div class="pure-u-1-2">
<input type="button" id="remove" class = "genericbutton pure-button wide"
  value="Remove Biomass Calculation" onClick="remov();"/>
</div>
<!--
<div class="pure-u-1">
<input type="button" id="Avg" value="Calculate" class="genericbutton pure-button wide" onClick="calculate(); calculate2();"/>
</div>
-->
</div>
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
//   calculate();
   calculate2();
}
</script>


<div class="pure-control-group">
<label for="average"> Biomass Pounds Per Acre:</label>
<input type="text" value=0 readonly id="average" class="textbox25 mobile-input" name="average" >
</div>
<div class="pure-control-group">
<label for="average"> Total Biomass Pounds:</label>
<input type="text" value=0 readonly id="total89" class="textbox25 mobile-input" name="total89" >
</div>
<div class="pure-control-group">
<label for"incorp20"> Incorporation Tool:</label>
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

<div class="pure-control-group">
<label for="tractor"> Tractor: </label>
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

<div class="pure-control-group">
<label for="numPasses"> Number of Passes: </label>
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

<div class="pure-control-group">
<label for="percTilled"> % of Field Tilled: </label>
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

<div class="pure-control-group">
<label for="minutes"> Minutes in Field: </label>
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


<script type="text/javascript">
var container = document.getElementById('container');
function addMeasurement() {
   num++;
/*
   var input = document.createElement('input'),
   div = document.createElement('div');
   input.type = "text";
   input.className="textbox4 mobile-input-half";
   input.value=0;
   input.id="avg"+num;
   div.id="div"+num;
   div.appendChild(input);
*/
   div = document.createElement('div');
   div.innerHTML = "<div class='pure-control-group'><label>Pounds per 4 square feet:</label>" +
      "<input type='text' id='avg" + num + "' value=0 onkeyup='calculate2();'></div>";
   container.appendChild(div);
};

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
        var avg;
        if (num > 0) {
           avg = ((total/num)*10890).toFixed(2);
        } else {
           avg = 0;
        }
document.getElementById('average').value = avg;
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
// console.log(xmlhttp.responseText+"responsetext");
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
/*
        for (i = 1; i <= num; i++) {
          con+= document.getElementById("crop"+i).value + "\n";
        }
*/
        con += document.getElementById("listArea").value + "\n";
        
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


<div class="pure-control-group">
<label for="comments"> Comments: </label>
<textarea name="comments" id="comments"
cols=30 rows=5>
</textarea>
</div>
</fieldset>
<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" value="Submit" name="submit" class="submitbutton pure-button wide" onclick="return show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "incorpReport.php?tab=soil:soil_fert:soil_cover:soil_coverincorp:coverincorp_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table"></form>
</div>
</div>
<?php
if (isset($_POST['submit'])) {
   $acre=43560;
   $avg=4;
   $fieldID = escapehtml($_POST['fieldID']);
   $seedDate = escapehtml($_POST['seeddate']);
   $incorpTool = escapehtml($_POST['tool']);
   $aver = escapehtml($_POST['average']);
   $comments = escapehtml($_POST['comments']);
   echo "<script>calculate2();</script>";

	// Insert into coverKill_master
	$sql = "INSERT INTO coverKill_master(killDate, incorpTool, totalBiomass, comments, fieldID, seedDate) 
		VALUES('".$_POST['year']."-".$_POST['month']."-".$_POST['day']."', '".$incorpTool."', 
			(".$aver.")*(SELECT area_seeded FROM coverSeed_master 
			WHERE fieldID='".$fieldID."' AND seedDate='".$seedDate."')
			/100*(SELECT size FROM field_GH WHERE fieldID='".$fieldID."'), 
		'".$comments."', '".$fieldID."', '".$seedDate."')";
	$result1 = mysql_query($sql);
	echo mysql_error();

	// Inert into coverKill
	$count = 1;
	$id = mysql_insert_id();
        $crops = explode("\n", $_POST['listArea']);
        foreach ($crops as $crp) {
	 	$sql = "INSERT INTO coverKill(coverCrop, id) 
			VALUES ('".trim($crp)."', ".$id.");";
		$result = mysql_query($sql);
		echo mysql_error();
                $result1 = $result1 && $result;
        }
/*
	$numCrops = $_POST['numCrops'];
	while ($count <= $numCrops) {
		$crop = escapehtml($_POST['crop'.$count]);
		$sql = "INSERT INTO coverKill(coverCrop, id) 
			VALUES ('".$crop."', ".$id.");";
		$result = mysql_query($sql);
		echo mysql_error();
		$count++;
	}
*/

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
