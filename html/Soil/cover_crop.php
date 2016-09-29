<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class='pure-form pure-form-aligned' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_input" >
<center>
<h2> Input Cover Crop Seeding Record</h2>
</center>
<div class="pure-control-group">
<label for='date'> Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
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

<div class="pure-control-group" id="field">
<label for="fieldID"> Field ID: </label>
<select name ="fieldID" id="fieldID" onChange="callAll();" class='mobile-select'>
<option value = 0 selected disabled> FieldID</option>
<?php
$result=$dbcon->query("Select fieldID from field_GH where active=1");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';
?>
<div class="pure-control-group">
<label for="percent"> Percent of Field Seeded:</label>
<?php
echo '<select name ="percent" id="percent" onChange="callAll();">';
$result= 10;
while ($result <= 100) {
echo "\n<option value= \"$result\">$result</option>";
$result= $result + 10;
}
echo '</select>';
echo '</div>';
?>
<div class="pure-control-group" id="seedM">
<label for="method">Seeding Method:</label>
<select name ="seed_method" id="seed_method" onChange="callAll();" class='mobile-select'> 
<option value= 0 selected disabled> Seeding Method </option>
<?php
$result=$dbcon->query("select seed_method from seedingMethod");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
echo "\n<option value= \"$row1[seed_method]\">$row1[seed_method]</option>";
}

echo '</select>';
echo '</div>';
?>
<div class="pure-control-group" id="incorp_toolis">
<label for="incorp">Incorporation Tool:</label>
<select name ="incorp_tool" id="incorp_tool" class='mobile-select'>
<option value = 0 selected disabled> Incorporation Tool </option>
<?php
$result=$dbcon->query("Select tool_name from tools");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo '</select>';
echo '</div>';
?>

<div class="pure-control-group" id="tractor2">
<label for="tractor"> Tractor: </label>
   <select name="tractor" id="tractor" class='mobile-select'>
      <option value=0 selected disabled> Tractor </option>
      <?php
      $result = $dbcon->query("Select tractorName from tractor where active = 1");
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
         echo "\n<option value=\"$row[tractorName]\">$row[tractorName]</option>";
      }
      echo '</select>';
      echo '</div>';
      ?>
   </select>
</div>

<div class="pure-control-group" id="numPasses2">
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

<div class="pure-control-group" id="minutes2">
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


<br clear="all"/>
<table id='covercrop' name='covercrop' class='pure-table pure-table-bordered'>
 <thead><tr><th>Species</th>
<?php
if ($_SESSION['seed_order']) {
  echo "<th>Seed Code</th>";
}
?>
<th>Rate of Seed</th><th>Total Pounds</th></tr></thead>
<tbody></tbody>
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
      var table = document.getElementById("covercrop").getElementsByTagName('tbody')[0];
      var row = table.insertRow(numRows - 1);
      row.id      = "row" + numRows;
      row.name      = "row" + numRows;
      var cell0    = row.insertCell(0);
      cell0.innerHTML = '<div class="styled-select wide" id="cropDiv'+numRows+'"><select name ="crop'+
         numRows+'" id="crop'+numRows+'" onChange="addPounds(\'num_poundsDiv'+numRows+
         '\',\''+numRows+'\'); addTotalPound(\'id'+numRows+'\',\''+numRows+
         '\');get_code(' + numRows + ');" class="wide">'+
         '<option value = 0 selected disabled>Species</option>'+
         '<?php
            $result=$dbcon->query("Select crop from coverCrop where active = 1");
            while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
               echo "<option value= \"$row1[crop]\">$row1[crop]</option>";
            }
         ?>'+'</select></div>';
      var col = 1;
      if (order) {
         var ccell = row.insertCell(col);
         col++;
         ccell.innerHTML="<div class='styled-select' id ='codediv" + numRows + 
            "''>  <select name= 'code" +numRows + "' id= 'code" + numRows + 
            "' class='wide' style='width:100%'><option value='N/A'>Not Available</option>" +
            "</select> </div>";
      }
      var cell1   = row.insertCell(col);
      col++;
      cell1.innerHTML = '<div class="styled-select"  id="num_poundsDiv'+numRows+'"><select name="numpounds'+numRows+'" id="numpounds'+numRows+'" onChange="addTotalPound(\'id'+numRows+'\',\''+numRows+'\');" class="wide">'+
         '<option value = 0 selected disabled> Rate of Seed </option></select></div>';
      var cell2    = row.insertCell(col);
      col++;
      cell2.innerHTML = '<div id="idDiv'+numRows+'"><input type="text"' +
       ' class="wide" ' +
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
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" id="add" name="add" class="genericbutton pure-button wide" onClick="addRow();" value="Add Row">
</div>
<div class="pure-u-1-2">
<input type="button" id="remove" name="remove" class="genericbutton pure-button wide" onClick="removeRow();" value="Remove Row">
</div>
</div>
<br clear="all"/>


<script type="text/javascript">
function addPounds(id,num) {
   console.log('start function');   
   var newdiv = document.getElementById(id);
   var e = document.getElementById("crop"+num);
   var f = document.getElementById("seed_method");
   
   if(e.value!=0){
   var crop= encodeURIComponent(e.value);
   var method= encodeURIComponent(f.value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET","update_pounds1.php?crop="+crop+"&method="+method,false);
   xmlhttp.send();
   newdiv.innerHTML="<select name='numpounds"+num+"' id='numpounds"+num+"'  onChange='addTotalPound(\"id"+num+"\",\""+num+"\");' class='mobile-select'>"+xmlhttp.responseText+"</select>";
   }

}

   function addTotalPound(id,num){
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
      
<div class="pure-control-group">
<label for="comments"> Comments:</label>
<textarea name="comments" rows="5" cols="30">
</textarea>
</div>
<br clear="all"/>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" name="submit" class="submitbutton pure-button wide" id="submit" value="Submit" onclick= " return show_confirm();  ">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "coverReport.php?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>
</div>
</div>
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
      $dec = "update coverSeedInventory set inInventory = inInventory - :pound".
         " where crop = :crop and code = :code";
      try {
         $stmt = $dbcon->prepare($dec);
         while ($count <= $numRows){
            $crop = escapehtml($_POST['crop'.$count]);
            $code = escapehtml($_POST['code'.$count]);
            $pound = escapehtml($_POST['pound'.$count]);
            $var = "select variety from coverSeedInventory where code ='".$code."' and crop = '".$crop."'";
            $vr = $dbcon->query($var);
            if ($vrow = $vr->fetch(PDO::FETCH_ASSOC)) {
               $variety = $vrow['variety'];
            } else {
               $variety = "No Variety";
            }
            if ($comments != "") {
               $comments .= "<br>";
            }
            $comments .= "Seed Code for ".$crop.": ".$code." (".escapehtml($variety).")";
            if ($code != "N/A") {
               $stmt->bindParam(':pound', $pound, PDO::PARAM_STR);
               $stmt->bindParam(':crop', $crop, PDO::PARAM_STR);
               $stmt->bindParam(':code', $code, PDO::PARAM_STR);
               $stmt->execute();
            }
            $count++;
         }
       } catch (PDOException $e) {
          phpAlert('', $e);
          die();
       }
   }
   $sqlcoverSeedMaster = "Insert into coverSeed_master (seed_method,incorp_tool,".
      "comments,seedDate,fieldID,area_seeded) values('".$seed_method."', '".
      $incorp_tool."','".$comments."','".$_POST['year']."-".$_POST['month'].
      "-".$_POST['day']."','".$fieldID."', $percent)";
   try {
      $stmt = $dbcon->prepare($sqlcoverSeedMaster);
      $stmt->execute();
   } catch (PDOException $e) {
       phpAlert('', $e);
       die();
   }
   $count = 1;
   $id = $dbcon->lastInsertId();
   $sql = "Insert into coverSeed(crop,seedRate,num_pounds,id) values(:crop, :numpounds, :pound, :id);";
   try {
      $stmt = $dbcon->prepare($sql);
      while ($count <= $numRows){
         $crop = escapehtml($_POST['crop'.$count]);
         $numpounds = escapehtml($_POST['numpounds'.$count]);
         $pound = escapehtml($_POST['pound'.$count]);
         $stmt->bindParam(':crop', $crop, PDO::PARAM_STR);
         $stmt->bindParam(':numpounds', $numpounds, PDO::PARAM_STR);
         $stmt->bindParam(':pound', $pound, PDO::PARAM_STR);
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
         $count++;
      }
   } catch (PDOException $e) {
       phpAlert('', $e);
       die();
   }
   // Insert into tillage
   $tractor = escapehtml($_POST['tractor']);
   $numPasses = escapehtml($_POST['numPasses']);
   $minutes = escapehtml($_POST['minutes']);
   try {
      $sql = "INSERT into tillage(tractorName, fieldID, tilldate, tool, num_passes, comment, minutes, percent_filled)
      values
      ('".$tractor."', '".$fieldID."', '".$_POST['year']."-".$_POST['month']."-".$_POST['day']."',
      '".$incorp_tool."', ".$numPasses.", '".$comments."', ".$minutes.", ".$percent.");";
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $e) {
       phpAlert('', $e);
       die();
   }

   echo '<script> showAlert("Cover Crop Seeding Record Entered Successfully") </script>';
 
} 
?>
