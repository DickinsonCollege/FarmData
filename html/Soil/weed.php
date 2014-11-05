<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' id='form'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_weed:weed_input">

<h3> Weed Scouting Input Form </h3>
<br clear="all"/>
<label for='date'> Date:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<input type="hidden" name="hid" id="hid">
<br clear="all"/>
<label style="float:left;" for="species"> Add New Weed Species</label>
<br clear="all"/>
<div id="container"></div>
<br clear="all" />
<table name="fieldTable" id="fieldTable">
   <tr><th>Field</th><th>Weed Species</th><th>infestation</th><th>% to seed</th></tr>   
</table>
<script type="text/javascript">
var numRows=0;
function addRow(){
   numRows++;
   var table = document.getElementById("fieldTable");
   var row = table.insertRow(numRows);
   row.id = "row" + numRows;
   row.name = "row" + numRows;
   var cell0 = row.insertCell(0);
   cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+numRows+'"> <select name="fieldID'+numRows+
                     '" id="fieldID'+numRows+'" class="mobile-select">'+
                     '<option value= 0 selected disabled> FieldID </option>'+
                     '<?php
                         $result=mysql_query("select fieldID from field_GH");
                        while($row1 = mysql_fetch_array($result)){
                           echo '<option value="'.$row1[fieldID].'">'.$row1[fieldID].'</option>';
                        }
                      ?>' + '</select></div>';
   var cell1 = row.insertCell(1);
   cell1.innerHTML = '<div class="styled-select" id="speciesDiv'+numRows+'"> <select class="mobile-select" name="species'+numRows+'" id="species'+numRows+'">'+
                     '<option value = 0 selected disabled>weed species</option>'+
                     '<?php
                        $sql = 'Select weedName from weed';
                        $result = mysql_query($sql);
                        while ($row1 =  mysql_fetch_array($result)){
                           //echo $row1[weedName];
                           echo '<option value="'.$row1['weedName'].'">'.$row1['weedName'].'</option>';
                        }      
                      ?>' + '</select></div>';
   var cell2 = row.insertCell(2);
   cell2.innerHTML = '<div class="styled-select" id="infestDiv'+numRows+'"><select class="mobile-select" name="infest'+numRows+'" id="infest'+numRows+'"><option value = 0 selected disabled> Infestation</option> <option>0</option> <option>1</option> <option>2</option> <option>3</option> </select></div>';
   var cell3 = row.insertCell(3);
   cell3.innerHTML = '<div class="styled-select" id="g2seedDiv'+numRows+'"><select class="mobile-select" name="g2seed'+numRows+'" id="g2seed'+numRows+'"><option value = 0 selected disabled> %ToSeed</option> <option>0</option> <option>25</option> <option>50</option> <option>75</option> <option>100</option> </select></div>';

}
addRow();
function removeRow() {
   if (numRows > 0) {
      var field=document.getElementById('fieldID' + numRows);
      field.parentNode.removeChild(field);
      var species=document.getElementById('species' + numRows);
      species.parentNode.removeChild(species);
      var infest=document.getElementById('infest' + numRows);
      infest.parentNode.removeChild(infest);
      var g2seed=document.getElementById('g2seed' + numRows);
      g2seed.parentNode.removeChild(g2seed);
      var table = document.getElementById("fieldTable");
      table.deleteRow(numRows);
      numRows--;
   }
}
</script>
<!--<br clear="all"/>
<label for="fieldID"> Field ID: &nbsp;</label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID">
<option value = 0 selected disabled> FieldID</option>
<?php
/*$result=mysql_query("Select fieldID from field_GH");
while ($row1 =  mysql_fetch_array($result)){
   echo "\n<option value= \"$row1[fieldID]\">$row1[fieldID]</option>";
}
echo '</select>';
echo '</div>';*/
?>
<br clear="all"/>
<label style="float:left;" for="species"> Add New Weed Species</label>
<br clear="all"/><br>
<div id="container"></div>
<br clear="all"/>-->

<!--
<table name="weedTable" id="weedTable">
<tr>
<th>Weed Species</th>
<th>Infestation</th>
<th>% To Seed</th>
</tr>
</table>
-->
<br clear="all"/>
<input style="float:left;" class = "genericbutton" type="button" id="add" value="Add Species" onclick="addRow();"/>
<!--<script type="text/javascript">
var num=0;
var input=document.createElement('input');
var divv=document.createElement('div');
divv.id="hidd";
input.type="hidden";
input.id="hidden";
input.name="hid";
var container = document.getElementById('container');
//   container.style.cssFloat="left";
container.appendChild(input);
console.log(input);
//document.getElementById('add').onclick=

   function addbox () {
   num++;
   document.getElementById('hidden').value=num;
   var div2=document.createElement('div');
   div2.id="div2ID"+num;
   addSpecies(num,div2);
   addInfestation(num,div2);
   addGToSeed(num,div2);
        container.appendChild(div2);
   var space= document.createElement('div');
   space.id="space"+num;
   space.innerHTML="<br clear=\"all\"/ >";
        container.appendChild(space);
}
function addInfestation(num,div2) {
        var div1 = document.createElement('div');
        div1.className="styled-select";  
        div1.innerHTML="<select name=\"infest"+num+"\" id=\"infest"+num+"\"><option value = '' selected disabled> Infestation</option> <option>0</option> <option>1</option> <option>2</option> <option>3</option> </select>";
   div1.id="Infest"+num;
        div2.appendChild(div1);
};

function addGToSeed(num,div2) {
   var div1 = document.createElement('div');
   div1.className="styled-select";  
   div1.innerHTML="<select name=\"g2seed"+num+"\"  id=\"g2seed"+num+"\"><option value = '' selected disabled> %ToSeed</option> <option>0</option> <option>25</option> <option>50</option> <option>75</option> <option>100</option> </select>";
   div1.id="GtoSeed"+num;
   div2.appendChild(div1);

};
function addSpecies(num,div2) {
   var div1 = document.createElement('div');
   div1.className="styled-select";   
   var Php ="<?php
   //$sql = 'Select weedName from weed';
   //$result = mysql_query($sql);
   //while ($row1 =  mysql_fetch_array($result)){
   //   echo '\n<option value= \"'.$row1['weedName'].'\">'.$row1['weedName'].'</option>';
   //}
   //echo '<br clear=\"all\"/>'; echo '<br clear=\"all\"/>';?>";
        div1.innerHTML="<select name=\"species"+num+"\"  id=\"species"+num+"\"><option value = '' selected disabled> Weed Species</option>" +Php;
   div1.id="spe"+num;
        div2.appendChild(div1);
};
addbox();

</script>-->

<!--<script>
function remov() {
   var elem = document.getElementById("container");
   elem.removeChild(document.getElementById("div2ID"+num));
   var elem5 = document.getElementById("space"+num);
   elem.removeChild(elem5);
   num--;
}
</script>-->
<input type="button" id="remove" class="genericbutton"  value="Remove Species" onClick="removeRow();"/>


<br clear="all"/>
<br clear="all"/>
<label for="comments"> Comments:&nbsp; </label>
<br clear="all"/>
<textarea name="comments" rows="10" cols="30" id="comments"></textarea>
<br clear="all"/>
<br clear="all"/>
<script type="text/javascript">
function show_confirm() {
   var hid = document.getElementById("hid");
   hid.value = numRows;
   /*var i = document.getElementById("fieldID");
   var strUser3 = i.value;
   if(checkEmpty(strUser3)) {
      alert("Please Select a FieldID");
      return false;
   }
   var con="Field ID: "+ strUser3+ "\n";*/
   var i = document.getElementById("month");
   var strUser3 = i.options[i.selectedIndex].text;
   var con="Scout Date: "+strUser3+"-";
   var i = document.getElementById("day");
   var strUser3 = i.options[i.selectedIndex].text;
   con=con+strUser3+"-";
   var i = document.getElementById("year");
   var strUser3 = i.options[i.selectedIndex].text;
   con=con+strUser3+"\n\n";
   var a=1;
   var allweeds = [];
   while (a <= numRows) {
      var i = document.getElementById("fieldID"+a);
      var strUser3 = i.value;
      if(checkEmpty(strUser3)) {
         alert("Please Select a FieldID in row: "+a);
         return false;
      }
      con=con + "FieldID: "+ strUser3+ "\n";
      
      var i = document.getElementById("species"+a);
      var spec = i.value;
      allweeds[a - 1] = spec;
      if(checkEmpty(spec)) {
         alert("Please Select a Weed Species in Row: " + a);
         return false;
      }
      con=con+"Weed Species "+a+": "+spec+"\n";
      var i = document.getElementById("infest"+a);
      var infest = i.value;
      if(checkEmpty(infest) && infest != 0) {
      // if(checkEmpty(infest)) {
         alert("Please Select an Infestation Level for " + spec);
         return false;
      }
      con=con+"Infestation Level: " + infest +"\n";
      var i = document.getElementById("g2seed"+a);
      var toseed = i.value;
      if (checkEmpty(toseed) && toseed != 0) {
      // if (checkEmpty(toseed)) {
         alert("Please Select a Gone to Seed Percentage for "+ spec);
         return false;
      }
      con=con+"Gone to Seed Percentage: " + toseed +"\n\n";
      a++;
      }       
   allweeds.sort();
   for (i = 0; i < allweeds.length - 1; i++) {
        if (allweeds[i] == allweeds[i + 1]) {
           alert("Error: same weed species entered twice!");
         return false;
      }
   }

   var i = document.getElementById("comments").value;
   con=con+"Comments: "+ i+ "\n";

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>
<input type="submit" name="submit" class="submitbutton" value="Submit" onClick="return show_confirm();">
<?php
echo "</form>";
echo '<form method="POST" action = "weedReport.php?tab=soil:soil_scout:soil_weed:weed_report"><input type="submit" class="submitbutton" value = "View Table"></form>';
if (isset($_POST['submit'])) {
   $comments = escapehtml($_POST['comments']);
   /*
   $fieldID = escapehtml($_POST['fieldID']);
   $sql="Insert into weedScout(sDate, fieldID, weed, infestLevel, goneToSeed, comments) values ( '".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID.
      "',weedName,0,0,'".$comments."' from weed)";
   $result=mysql_query($sql);
   if (!$result) {
   echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   } else {
   echo "<script>showAlert(\"Data Entered Successfully!\\n\");</script>\n";
   }*/
   $var= $_POST['hid'];
   while ($var>0) {
      $fieldID = escapehtml($_POST['fieldID'.$var]);  
      $infest = escapehtml($_POST['infest'.$var]);
      $g2seed = escapehtml($_POST['g2seed'.$var]);
      $species = escapehtml($_POST['species'.$var]);
      $sql = "insert into weedScout(sDate, fieldID, weed, infestLevel, gonetoSeed, comments) values ('".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$species."', ".$infest.", ".$g2seed.",'".$comments."')";
/*
      $update= "Update weedScout set infestLevel='".$infest.
           "', goneToSeed='".$g2seed."' where weed='".$species.
           "' and sDate='".$_POST['year']."-".$_POST['month']."-".
           $_POST['day']."' and fieldID='".$fieldID."'";
*/
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Data Entered Successfully!\\n\");</script>\n";
      }
      $var--;
   }
}
?>
