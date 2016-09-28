<?php session_start();?>
<?php

include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';

$id = $_GET['id'];
$origFieldID = $_GET['fieldID'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];

$sqlMaster = "select id, year(seedDate) as yr, month(seedDate) as mth, day(seedDate) as dy,area_seeded, seed_method, incorp_tool, fieldID, seedDate, comments from coverSeed_master where id=".$id;

$sqldata = $dbcon->query($sqlMaster);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);

$id = $row['id'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$area_seeded = $row['area_seeded'];
$seed_method = $row['seed_method'];
$incorp_tool = $row['incorp_tool'];
$fieldID = $row['fieldID'];
$comments = $row['comments'];

echo "<form name='form' class='pure-form pure-form-aligned' method='POST' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report".
   "&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id."&fieldID=".encodeURIComponent($origFieldID)."\">";

echo "<center>";
echo "<H2> Edit Cover Crop Seeding Record </H2>";
echo "</center>";
echo '<div class="pure-control-group">';
echo '<label>Date:</label>';
echo '<select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</select>';
echo '<select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Field ID:</label>";
echo "<select name='fieldID' id='fieldID' onchange='callAll();'>";
echo "<option value\"=".$fieldID."\" selected>".$fieldID."</option>";
$sql = "SELECT fieldID FROM field_GH where active=1";
$sqldata = $dbcon->query($sql);
while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo "<label>Percent of Field Seeded:</label>";
echo "<input type='text' class='textbox2' name='area_seeded' id='area_seeded' value='".$area_seeded."' onchange='callAll();'>";
echo '</div>';

echo '<div class="pure-control-group">';
echo "<label>Seeding Method:</label>";
echo "<select name ='seed_method' id='seed_method' class='mobile-select' onchange='callAll();'>";
echo "<option value=".$seed_method.">".$seed_method."</option>";
$result = $dbcon->query("select seed_method from seedingMethod");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[seed_method]\">$row1[seed_method]</option>";
}

echo "</select>";
echo "</div>";

echo '<div class="pure-control-group">';
echo "<label>Incorporation Tool:</label>";
echo "<select name ='incorp_tool' id='incorp_tool' class='mobile-select'>";
echo "<option value ='".$incorp_tool."'>".$incorp_tool."</option>";
$result = $dbcon->query("Select tool_name from tools where type='INCORPORATION'");
while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
   echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo "</select>";
echo "</div>";
echo "<br clear='all'>";
echo "<br clear='all'>";

echo "<table name='covercrop' id='covercrop' class='pure-table pure-table-bordered'>";
echo "<thead><tr><th>Species</th><th>Seed Rate</th><th>Number Of Pounds</th></tr></thead>";
echo "<tbody>";
$sql = "select * from coverSeed where id=".$id;
$result = $dbcon->query($sql);
$numRows = 0;
while($row=$result->fetch(PDO::FETCH_ASSOC)){
   $numRows++;
   // generate options for crops
   $optionCrop = "";
   $sql = "SELECT crop FROM coverCrop where active = 1";
   $sqldata = $dbcon->query($sql);
   while ($rowCrop = $sqldata->fetch(PDO::FETCH_ASSOC)) {
      $optionCrop = $optionCrop."<option value='".$rowCrop['crop']."'>".$rowCrop['crop']."</option>";
   }
   // generate options for rate of seed
   $rate_of_seed_option ="";
   if ($seed_method=="DRILL") {
      $sql="Select drillRateMin,drillRateMax from coverCrop where crop='".$row['crop']."'";
      $result1=$dbcon->query($sql);
      while ($rowM=$result1->fetch(PDO::FETCH_ASSOC)) {
         $min=$rowM['drillRateMin'];
         $max=$rowM['drillRateMax'];
      }
   }else {
      $min = 0;
      $max = 0;
      $sql="Select brcstRateMin,brcstRateMax from coverCrop where crop='".$row['crop']."'";
      $result2=$dbcon->query($sql);
      while ($rowB=$result2->fetch(PDO::FETCH_ASSOC)) {
         $min=$rowB['brcstRateMin'];
         $max=$rowB['brcstRateMax'];
      }
   }
   $min2=$min;
   $min2." ".$max;
   while ($min2<=$max) {
      $min2Formated=number_format($min2,1,'.','');
      $rate_of_seed_option = $rate_of_seed_option."<option value=".$min2.">".$min2Formated."</option>";
      $min2=$min2+(($max-$min)/10);

   }   


   echo "<tr><td>";
   echo "<div class='styled-select' id='cropDiv".$numRows."'>";
      echo "<select name='crop".$numRows."' id='crop".$numRows."' onChange=\"addPounds('num_poundsDiv".$numRows."','".$numRows."'); addTotalPound('id".$numRows."','".$numRows."');\"  class='mobile-select'><option value='".$row[crop]."'>".$row[crop]."</option>".$optionCrop."</select>";
   echo "</div>";
   echo "</td><td>";
   echo "<div class='styled-select' id='num_poundsDiv".$numRows."'>";
      echo "<select name='numpounds".$numRows."' id='numpounds".$numRows."' onChange=\"addTotalPound('id".$numRows."','".$numRows."');\"  class='mobile-select'><option value='".$row[seedRate]."'>".$row[seedRate]."</option>".$rate_of_seed_option."</select>";
   echo "</div>";
   echo "</td><td>";
   echo "<div id='idDiv".$numRows."'>";
      echo "<input type='text' class='textbox25 mobile-input inside_table' id='id".$numRows."' name='pound".$numRows."' value='".$row[num_pounds]."'>";
   echo "</div>";
   echo "</td></tr>";
}
echo "</tbody></table>";
echo "<input type='hidden' value='".$numRows."' name='numRows' id='numRows'>";
?>
<script type='text/javascript'>
   var numRows=document.getElementById('numRows').value;
   function addRow(){
      numRows++;
      document.getElementById('numRows').value = numRows;
      var tab = document.getElementById("covercrop");
      var table = tab.getElementsByTagName('tbody')[0];
      var row = table.insertRow(numRows - 1);
      row.id      = "row" + numRows;
      row.name    = "row" + numRows;
      var cell0   = row.insertCell(0);
      cell0.innerHTML = '<div class="styled-select" id="cropDiv'+numRows+'"><select name ="crop'+numRows+'" id="crop'+numRows+'" onChange="addPounds(\'num_poundsDiv'+numRows+'\',\''+numRows+'\'); addTotalPound(\'id'+numRows+'\',\''+numRows+'\');" class="mobile-select">'+
         '<option value = 0 selected disabled>Species</option>'+
         '<?php
            $result=$dbcon->query("Select crop from coverCrop where active = 1");
            while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
               echo "<option value= \"$row1[crop]\">$row1[crop]</option>";
            }
         ?>'+'</select></div>';
      var cell1   = row.insertCell(1);
      cell1.innerHTML = '<div class="styled-select"  id="num_poundsDiv'+numRows+'"><select name="numpounds'+numRows+'" id="numpounds'+numRows+'" onChange="addTotalPound(\'id'+numRows+'\',\''+numRows+'\');" class="mobile-select">'+
         '<option value = 0 selected disabled> Rate of Seed </option></select></div>';
      var cell2   = row.insertCell(2);
      cell2.innerHTML = '<div id="idDiv'+numRows+'"><input type="text" class="textbox25 mobile-input inside_table"  id="id'+numRows+'" name="pound'+numRows+'"  value="">';   
   }
   
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
         document.getElementById('numRows').value = numRows;
      }
   }
   function addPounds(id,num) {
      console.log('start function');   
      var newdiv = document.getElementById(id);
      var e = document.getElementById("crop"+num);
      var method = document.getElementById("seed_method").value;
      
      if(e.value!=0){
      var crop= e.value;
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET","update_pounds1.php?crop="+encodeURIComponent(crop)+"&method="+
           encodeURIComponent(method),false);
      xmlhttp.send();
      newdiv.innerHTML="<select name='numpounds"+num+"' id='numpounds"+num+"'  onChange='addTotalPound(\"id"+num+"\",\""+num+"\");' class='mobile-select'>"+xmlhttp.responseText+"</select>";
      }
   
   }

   function addTotalPound(id,num){
      var newdiv = document.getElementById(id);
   
      var fieldID = encodeURIComponent(document.getElementById("fieldID").value);
      var percent = document.getElementById("area_seeded").value;  
      var rate = document.getElementById("numpounds"+num).value;
   
      xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET","updateCalculatePound.php?percent="+percent+"&fieldID="+fieldID+"&rate="+rate,false);
        xmlhttp.send();
   newdiv.value=xmlhttp.responseText;
}  
function callAll() {
   var count = 1;
   console.log('call All is called');
   while (count <= numRows){
      addPounds('num_poundsDiv'+count,count);
      addTotalPound('id'+count,count);
      count++;
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
<br clear="all"/>
<?php

echo '<div class="pure-control-group">';
echo '<label>Comments:</label>';
echo "<textarea rows=\"5\" cols=\"30\" name = \"comments\" id = \"comments\">";
echo $comments;
echo "</textarea>";
echo "</div>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';

echo "<input type='submit' name='submit' value='Update Record' class = 'submitbutton pure-button wide'>";
echo "</form>";
if ($_POST['submit']) {
   $numberOfRows = escapehtml($_POST['numRows']);
   $comments = escapehtml($_POST['comments']);
   $year = escapehtml($_POST['year']);
   $month = escapehtml($_POST['month']);
   $day = escapehtml($_POST['day']);
   $crop1 = escapehtml($_POST['crop1']);
   $seedRate1 = escapehtml($_POST['seedRate1']);
   $num_pounds1 = escapehtml($_POST['num_pounds1']);
   $crop2 = escapehtml($_POST['crop2']);
   $seedRate2 = escapehtml($_POST['seedRate2']);
   $num_pounds2 = escapehtml($_POST['num_pounds2']);
   $fieldID = escapehtml($_POST['fieldID']);
   $area_seeded = escapehtml($_POST['area_seeded']);
   $seed_method = escapehtml($_POST['seed_method']);
   $incorp_tool = escapehtml($_POST['incorp_tool']);
   
   $sqlMaster = "update coverSeed_master set seed_method='".$seed_method."', incorp_tool='".$incorp_tool."', comments='".$comments."', seedDate='".$year."-".$month."-".$day."', fieldID='".$fieldID."', area_seeded=".$area_seeded." where id=".$id;   
   
   try {
      $stmt = $dbcon->prepare($sqlMaster);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
     
   $sqlDelete = "delete from coverSeed where id=".$id;
   try {
      $stmt = $dbcon->prepare($sqlDelete);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   
   $sqlAdd = "Insert into coverSeed (crop, seedRate, num_pounds, id) values".
      "(:crop, :seedRate, :numpounds, :id)";
   try {
      $stmt = $dbcon->prepare($sqlAdd);
      $count=1;
      while($count <= $numberOfRows){
         $crop = escapehtml($_POST['crop'.$count]);
         $seedRate = escapehtml($_POST['numpounds'.$count]);
         $numpounds = escapehtml($_POST['pound'.$count]);
         $stmt->bindParam(':crop', $crop, PDO::PARAM_STR);
         $stmt->bindParam(':seedRate', $seedRate, PDO::PARAM_STR);
         $stmt->bindParam(':numpounds', $numpounds, PDO::PARAM_STR);
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
         $count++;
      }
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   echo '<meta http-equiv="refresh" content="0;URL=coverTable.php?year='.$origYear.'&month='.$origMonth.
      '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
      "&fieldID=".encodeURIComponent($origFieldID).
      "&tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report\">";
}
?>
