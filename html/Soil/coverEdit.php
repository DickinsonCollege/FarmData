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

$sqldata = mysql_query($sqlMaster) or die(mysql_error());
$row = mysql_fetch_array($sqldata);

$id = $row['id'];
$curYear = $row['yr'];
$curMonth = $row['mth'];
$curDay = $row['dy'];
$area_seeded = $row['area_seeded'];
$seed_method = $row['seed_method'];
$incorp_tool = $row['incorp_tool'];
$fieldID = $row['fieldID'];
$comments = $row['comments'];

echo "<form name='form' method='POST' action=\"".$SERVER['PHP_SELF'].
   "?tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report".
   "&year=".$origYear."&month=".$origMonth."&day=".$origDay.
   "&tyear=".$tcurYear."&tmonth=".$tcurMonth."&tday=".$tcurDay.
   "&id=".$id."&fieldID=".encodeURIComponent($origFieldID)."\">";

echo "<H3> Edit Cover Crop Seeding Record </H3>";
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<label>Date:&nbsp</label>';
echo '<div class="styled-select"><select name="month" id="month">';
echo '<option value='.$curMonth.' selected>'.date("F", mktime(0,0,0, $curMonth,10)).' </option>';
for($mth = 1; $mth <= 12; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="day" id="day">';
echo '<option value='.$curDay.' selected>'.$curDay.' </option>';
for($day = $curDay - $curDay+1; $day <= 31; $day++) {echo "\n<option value =\"$day\">$day</option>";
}
echo '</div></select>';
echo '<div class="styled-select"><select name="year" id="year">';
echo '<option value='.$curYear.' selected>'.$curYear.'</option>';
for($yr = $curYear - 4; $yr < $curYear+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</div></select>';
echo '<br clear="all"/>';
echo '<br clear="all"/>';

echo "<label>Field ID:&nbsp</label>";
echo "<div class='styled-select'><select name='fieldID' id='fieldID' onchange='callAll();'>";
echo "<option value\"=".$fieldID."\" selected>".$fieldID."</option>";
$sql = "SELECT fieldID FROM field_GH where active=1";
$sqldata = mysql_query($sql) or die();
while ($row = mysql_fetch_array($sqldata)) {
   echo "<option value=\"".$row['fieldID']."\">".$row['fieldID']."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

echo "<label>Percent of Field Seeded:&nbsp</label>";
echo "<input type='text' class='textbox2' name='area_seeded' id='area_seeded' value='".$area_seeded."' onchange='callAll();'>";
echo "<br clear='all'>";

echo "<label>Seed Method:&nbsp</label>";
echo "<div class='styled-select' id='seedM'>";
echo "<select name ='seed_method' id='seed_method' class='mobile-select' onchange='callAll();'>";
echo "<option value=".$seed_method.">".$seed_method."</option>";
$result=mysql_query("select seed_method from seedingMethod");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[seed_method]\">$row1[seed_method]</option>";
}

echo "</select>";
echo "</div>";
echo "<br clear='all'>";

echo "<label>Incorporation Tool:&nbsp</label>";
echo "<div class='styled-select' id='incorp_toolis'>";
echo "<select name ='incorp_tool' id='incorp_tool' class='mobile-select'>";
echo "<option value ='".$incorp_tool."'>".$incorp_tool."</option>";
$result=mysql_query("Select tool_name from tools where type='INCORPORATION'");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo "</select>";
echo "</div>";
echo "<br clear='all'>";
echo "<br clear='all'>";

echo "<table name='covercrop' id='covercrop'>";
echo "<tr><th>Species</th><th>Seed Rate</th><th>Number Of Pounds</th></tr>";
$sql = "select * from coverSeed where id=".$id;
$result = mysql_query($sql);
$numRows = 0;
while($row=mysql_fetch_array($result)){
   $numRows++;
   // generate options for crops
   $optionCrop = "";
   $sql = "SELECT crop FROM coverCrop where active = 1";
   $sqldata = mysql_query($sql) or die(mysql_error);
   while ($rowCrop = mysql_fetch_array($sqldata)) {
      $optionCrop = $optionCrop."<option value='".$rowCrop['crop']."'>".$rowCrop['crop']."</option>";
   }
   // generate options for rate of seed
   $rate_of_seed_option ="";
   if ($seed_method=="DRILL") {
      $sql="Select drillRateMin,drillRateMax from coverCrop where crop='".$row['crop']."'";
      $result1=mysql_query($sql);
      while ($rowM=mysql_fetch_array($result1)) {
         $min=$rowM['drillRateMin'];
         $max=$rowM['drillRateMax'];
      }
   }else {
      $min = 0;
      $max = 0;
      $sql="Select brcstRateMin,brcstRateMax from coverCrop where crop='".$row['crop']."'";
      $result2=mysql_query($sql);
      while ($rowB=mysql_fetch_array($result2)) {
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
echo "<input type='hidden' value='".$numRows."' name='numRows' id='numRows'>";
echo "</table>";
/*echo "<label>Crop2:&nbsp</label>";
echo "<div class='styled-select'><select name='crop2' id='crop2'>";
echo "<option value='".$crop2."' selected>".$crop2."</option>";
$sql = "SELECT crop FROM coverCrop where active = 1";
$sqldata = mysql_query($sql) or die();
while ($row = mysql_fetch_array($sqldata)) {
   echo "<option value='".$row['crop']."'>".$row['crop']."</option>";
}
echo "</div></select>";
echo "<br clear='all'>";

echo "<label>Seed Rate2:&nbsp</label>";
echo "<input type='text' class='textbox2' name='seedRate2' id='seedRate2' value='".$seedRate2."'>";
echo "<br clear='all'>";

echo "<label>Num Pounds 2:&nbsp</label>";
echo "<input type='text' class='textbox2' name='num_pounds2' id='num_pounds2' value='".$num_pounds2."'>";*/
?>
<script type='text/javascript'>
   var numRows=document.getElementById('numRows').value;
   function addRow(){
      numRows++;
      document.getElementById('numRows').value = numRows;
      var table   = document.getElementById('covercrop');
      var row     = table.insertRow(numRows);
      row.id      = "row" + numRows;
      row.name    = "row" + numRows;
      var cell0   = row.insertCell(0);
      cell0.innerHTML = '<div class="styled-select" id="cropDiv'+numRows+'"><select name ="crop'+numRows+'" id="crop'+numRows+'" onChange="addPounds(\'num_poundsDiv'+numRows+'\',\''+numRows+'\'); addTotalPound(\'id'+numRows+'\',\''+numRows+'\');" class="mobile-select">'+
         '<option value = 0 selected disabled>Species</option>'+
         '<?php
            $result=mysql_query("Select crop from coverCrop where active = 1");
            while ($row1 =  mysql_fetch_array($result)){
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
      xmlhttp.open("GET","/Soil/update_pounds1.php?crop="+encodeURIComponent(crop)+"&method="+
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
<input type="button" id="add" name="add" class="genericbutton" onClick="addRow();" value="Add Row">
<input type="button" id="remove" name="remove" class="genericbutton" onClick="removeRow();" value="Remove Row">
<br clear="all"/>
<br clear="all"/>
<?php

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
   
   $result = mysql_query($sqlMaster);
   echo mysql_error();
     
   $sqlDelete = "delete from coverSeed where id=".$id;
   mysql_query($sqlDelete) or die(mysql_error());
   
   $count=1;
   while($count <= $numberOfRows){
      $crop = escapehtml($_POST['crop'.$count]);
      $seedRate = escapehtml($_POST['numpounds'.$count]);
      $numpounds = escapehtml($_POST['pound'.$count]);
      $sqlAdd = "Insert into coverSeed (crop, seedRate, num_pounds, id) values('".$crop."', ".$seedRate.",".$numpounds.",".$id.")";

      mysql_query($sqlAdd) or die(mysql_error());
      $count++;
   }
   
   if(!$result){
       echo "<script>alert(\"Could not update data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
      echo "<script>alert(\"Entered data successfully!\");</script> \n";
      echo '<meta http-equiv="refresh" content="0;URL=coverTable.php?year='.$origYear.'&month='.$origMonth.
         '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.
         "&fieldID=".encodeURIComponent($origFieldID).
         "&tab=soil:soil_fert:soil_cover:soil_coverseed:coverseed_report\">";
   }
}
?>
