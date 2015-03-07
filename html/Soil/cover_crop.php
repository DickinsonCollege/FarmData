<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<html>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_input" >
<h3> Input Cover Crop Seeding Record</h3>
<br clear="all"/>
<label for='date'> Date:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<input type='hidden' value=0 id='numRows' name='numRows'>
<script type="text/javascript">
<?php
if ($_SESSION['seed_order']) {
   echo "var order = true;";
} else {
   echo "var order = false;";
}
?>

function show_confirm() {
     var numberOfRows = document.getElementById('numRows');
     numberOfRows.value= numRows;
     var i = document.getElementById("month");
     var strUser3 = i.options[i.selectedIndex].text;
      var con="Seeding Date: "+strUser3+"-";
     var i = document.getElementById("day");
     var strUser3 = i.options[i.selectedIndex].text;
     con=con+strUser3+"-";
     var i = document.getElementById("year");
     var strUser3 = i.options[i.selectedIndex].text;
     con=con+strUser3+"\n";
     var i = document.getElementById("fieldID");
     var strUser3 = i.options[i.selectedIndex].text;
     if(checkEmpty(i.value)) {
        alert("Please Enter Field");
        return false;
     }
     con=con+"FieldID: "+ strUser3+ "\n";
     var i = document.getElementById("percent").value;
     con=con+"Percent of Field Seeded: "+ i+ "\n";
     var i = document.getElementById("seed_method");
     var strUser3 = i.options[i.selectedIndex].text;
     if (checkEmpty(i.value)) {
         alert("Please Enter Seeding Method");
         return false;
     }   
     con=con+"Seeding Method: "+ strUser3+ "\n";
     var countRows = 1;
     for (countRows; countRows <= numRows; countRows++){
         var i = document.getElementById("crop"+countRows);
         if (checkEmpty(i.value)) {
             alert("Please Enter Crop "+countRows);
             return false;
         }
         var strUser3 = i.options[i.selectedIndex].text;
         con=con+"Cover Crop Species "+countRows+": "+ strUser3+ "\n";
         var i = document.getElementById("id"+countRows).value; 
         con=con+"Number of Pounds Seeded for Cover Crop Species "+countRows+": "+ i+ "\n\n";
     }
     var i = document.getElementById("tractor").value;
     if (checkEmpty(i)) {
         alert("Please Enter Tractor");
         return false;
     }
     con=con+"Tractor: "+ i+ "\n";
     var i = document.getElementById("incorp_tool").value;
     if (checkEmpty(i)) {
         alert("Please Enter Incorporation Tool");
         return false;
     }
     con=con+"Incorporation Tool: "+ i+ "\n";
     var i = document.getElementById("numPasses").value;
     if (checkEmpty(i)) {
         alert("Please Enter Number of Passes");
         return false;
     }
     con=con+"Number of Passes: "+ i+ "\n";
     var i = document.getElementById("minutes").value;
     if (checkEmpty(i)) {
         alert("Please Enter Minutes in Field");
         return false;
     }
     con=con+"Minutes in Field: "+ i+ "\n";
     return confirm("Confirm Entry:"+"\n"+con); 
}   
</script>

<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" onChange="callAll();" class='mobile-select'>
<option value = 0 selected disabled> FieldID</option>
<?php
$result=mysql_query("Select fieldID from field_GH where active=1");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="percent"> Percent of Field Seeded:&nbsp; </label>
<div class="styled-select" id="field">
<?php if(!$_SESSION['mobile']) {
echo '<select name ="percent" id="percent" onChange="callAll();">';
} else {
echo '<select name ="percent" id="percent" onChange="callAll();" class="mobile-select">';
}
$result= 10;
while ($result <= 100) {
echo "\n<option value= \"$result\">$result</option>";
$result= $result + 10;
}
echo '</select>';
echo '</div>'
?>
<br clear="all"/>
<label for="method">Seeding Method:&nbsp;</label>
<div class="styled-select" id="seedM">
<select name ="seed_method" id="seed_method" onChange="callAll();" class='mobile-select'> 
<option value= 0 selected disabled> Seeding Method </option>
<?php
$result=mysql_query("select seed_method from seedingMethod");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[seed_method]\">$row1[seed_method]</option>";
}

echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="incorp">Incorporation Tool:</label>
<br clear="all"/>
<div class="styled-select" id="incorp_toolis">
<select name ="incorp_tool" id="incorp_tool" class='mobile-select'>
<option value = 0 selected disabled> Incorporation Tool </option>
<?php
$result=mysql_query("Select tool_name from tools");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>

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


<br clear="all"/>
<table id='covercrop' name='covercrop'>
 <tr><th>Species</th>
<?php
if ($_SESSION['seed_order']) {
  echo "<th>Seed Code</th>";
}
?>
<th>Rate of Seed</th><th>Total Pounds</th></tr>
</table>
<script type='text/javascript'>
   var numRows=0;
function get_code(row) {
   if (order) {
      var crop = document.getElementById("crop" + row).value;
      xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "get_code.php?crop="+crop, false);
      xmlhttp.send();
      var codediv = document.getElementById("codediv" + row);
      codediv.innerHTML="<div class='styled-select' id ='codediv" + row + 
            "''>  <select name= 'code" +row + "' id= 'code" + row + 
            "' class='mobile-select' style='width:100%'>" + xmlhttp.responseText +
            "</select> </div>";
   }
}

   function addRow(){
      numRows++;
      var table    = document.getElementById('covercrop');
      var row      = table.insertRow(numRows);
      row.id      = "row" + numRows;
      row.name      = "row" + numRows;
      var cell0    = row.insertCell(0);
      cell0.innerHTML = '<div class="styled-select" id="cropDiv'+numRows+'"><select name ="crop'+
         numRows+'" id="crop'+numRows+'" onChange="addPounds(\'num_poundsDiv'+numRows+
         '\',\''+numRows+'\'); addTotalPound(\'id'+numRows+'\',\''+numRows+
         '\');get_code(' + numRows + ');" class="mobile-select">'+
         '<option value = 0 selected disabled>Species</option>'+
         '<?php
            $result=mysql_query("Select crop from coverCrop where active = 1");
            while ($row1 =  mysql_fetch_array($result)){
               echo "<option value= \"$row1[crop]\">$row1[crop]</option>";
            }
         ?>'+'</select></div>';
      var col = 1;
      if (order) {
         var ccell = row.insertCell(col);
         col++;
         ccell.innerHTML="<div class='styled-select' id ='codediv" + numRows + 
            "''>  <select name= 'code" +numRows + "' id= 'code" + numRows + 
            "' class='mobile-select' style='width:100%'><option value='N/A'>Not Available</option>" +
            "</select> </div>";
      }
      var cell1   = row.insertCell(col);
      col++;
      cell1.innerHTML = '<div class="styled-select"  id="num_poundsDiv'+numRows+'"><select name="numpounds'+numRows+'" id="numpounds'+numRows+'" onChange="addTotalPound(\'id'+numRows+'\',\''+numRows+'\');" class="mobile-select">'+
         '<option value = 0 selected disabled> Rate of Seed </option></select></div>';
      var cell2    = row.insertCell(col);
      col++;
      cell2.innerHTML = '<div id="idDiv'+numRows+'"><input type="text"' +
       ' class="textbox25 mobile-input inside_table" ' +
       ' id="id'+numRows+'" name="pound'+numRows+'"  value="">';   
   }
   addRow();
   function removeRow(){
      if (numRows > 0){
         var crop = document.getElementById('crop'+numRows);
         var cropDiv = document.getElementById('cropDiv'+numRows);
         cropDiv.removeChild(crop);
         var numPounds = document.getElementById('numpounds'+numRows);
         var numPoundsDiv = document.getElementById('num_poundsDiv'+ numRows);
         numPoundsDiv.removeChild(numPounds);
         var id = document.getElementById('id'+numRows);
         var idDiv = document.getElementById('idDiv'+numRows);
         idDiv.removeChild(id);
         var table = document.getElementById('covercrop');
         table.deleteRow(numRows);
         numRows--;
      }
   }
</script>
<br clear='all'>
<input type="button" id="add" name="add" class="genericbutton" onClick="addRow();" value="Add Row">
<input type="button" id="remove" name="remove" class="genericbutton" onClick="removeRow();" value="Remove Row">
<br clear="all"/>


<script type="text/javascript">
function addPounds(id,num) {
   console.log('start function');   
   var newdiv = document.getElementById(id);
   var e = document.getElementById("crop"+num);
   //var g = document.getElementById("fieldID");
   var f = document.getElementById("seed_method");
   
   if(e.value!=0){
   var crop= encodeURIComponent(e.value);
   //var fieldID= g.options[g.selectedIndex].text;
   var method= encodeURIComponent(f.value);
   //var percent=document.getElementById("percent").value;
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET","update_pounds1.php?crop="+crop+"&method="+method,false);
   xmlhttp.send();
      //console.log(xmlhttp.responseText);
   newdiv.innerHTML="<select name='numpounds"+num+"' id='numpounds"+num+"'  onChange='addTotalPound(\"id"+num+"\",\""+num+"\");' class='mobile-select'>"+xmlhttp.responseText+"</select>";
   }

}

   function addTotalPound(id,num){
   //console.log("I AM HEREi1111");
   var newdiv = document.getElementById(id);
   var rate=document.getElementById("numpounds"+num).value;
   
        var fieldID= encodeURIComponent(document.getElementById("fieldID").value);
        var percent=document.getElementById("percent").value;   
   
   xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET","updateCalculatePound.php?percent="+percent+"&fieldID="+fieldID+"&rate="+rate,false);
        xmlhttp.send();
   newdiv.value=xmlhttp.responseText;
}   
function callAll() {
   var count = 1;
   while (count <= numRows){
      addPounds('num_poundsDiv'+count,count);
      addTotalPound('id'+count,count);
      count++;
   }
}
</script>
<br clear="all"/>
      
<label for="comments"> Comments:&nbsp; </label>
<br clear="all"/>
<textarea name="comments" rows="10" cols="30">
</textarea>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit" class="submitbutton" id="submit" value="Submit" onclick= " return show_confirm();  ">
<?php
if(!empty($_POST['submit'])) {
   // Insert Into coverSeed_master
   $numRows = escapehtml($_POST['numRows']);
   $comments = escapehtml($_POST['comments']);
   $incorp_tool = escapehtml($_POST['incorp_tool']);
   $fieldID = escapehtml($_POST['fieldID']);
   $percent = escapehtml($_POST['percent']);
   $seed_method = escapehtml($_POST['seed_method']);
   if ($_SESSION['seed_order']) {
      $count = 1;
      while ($count <= $numRows){
         $crop = escapehtml($_POST['crop'.$count]);
         $code = escapehtml($_POST['code'.$count]);
         $pound = escapehtml($_POST['pound'.$count]);
         $var = "select variety from coverSeedInventory where code ='".$code."' and crop = '".$crop."'";
         $vr = mysql_query($var);
         echo mysql_error();
         if ($vrow = mysql_fetch_array($vr)) {
            $variety = $vrow['variety'];
         } else {
            $variety = "No Variety";
         }
         if ($comments != "") {
            $comments .= "<br>";
         }
         $comments .= "Seed Code for ".$crop.": ".$code." (".escapehtml($variety).")";
         if ($code != "N/A") {
            $dec = "update coverSeedInventory set inInventory = inInventory - ".
               $pound." where crop = '".$crop."' and code = '".$code."'";
            $decres = mysql_query($dec);
            echo mysql_error();
         }
         $count++;
      }
   }
   $sqlcoverSeedMaster = "Insert into coverSeed_master (seed_method,incorp_tool,".
      "comments,seedDate,fieldID,area_seeded) values('".$seed_method."', '".
      $incorp_tool."','".$comments."','".$_POST['year']."-".$_POST['month'].
      "-".$_POST['day']."','".$fieldID."', $percent)";
   $result = mysql_query($sqlcoverSeedMaster);
   echo mysql_error();
   $count = 1;
   $id = mysql_insert_id();
   while ($count <= $numRows){
      $crop = escapehtml($_POST['crop'.$count]);
      $numpounds = escapehtml($_POST['numpounds'.$count]);
      $pound = escapehtml($_POST['pound'.$count]);
      $sql = "Insert into coverSeed(crop,seedRate,num_pounds,id)".
               "values('".$crop."',".$numpounds.",".$pound.",".$id.");";
      $result1 = mysql_query($sql) or die(mysql_error());
      echo mysql_error();
      $count++;
   }
   // Insert into tillage
   $tractor = escapehtml($_POST['tractor']);
   $numPasses = escapehtml($_POST['numPasses']);
   $minutes = escapehtml($_POST['minutes']);
   $sql = "INSERT into tillage(tractorName, fieldID, tilldate, tool, num_passes, comment, minutes, percent_filled)
   values
   ('".$tractor."', '".$fieldID."', '".$_POST['year']."-".$_POST['month']."-".$_POST['day']."',
   '".$incorp_tool."', ".$numPasses.", '".$comments."', ".$minutes.", ".$percent.");";
   $result2=mysql_query($sql);
   echo mysql_error();

   if ($result && $result2) {
      echo '<script> showAlert("Cover Crop Seeding Record Entered Successfully") </script>';
   } else {
      echo '<script> alert("Could not enter data! Check input and try again.\n '.mysql_error().'") </script>';
   } 
} 
?>
</form>
<form method="POST" action = "coverReport.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report"><input type="submit" class="submitbutton" value = "View Table"></form>
