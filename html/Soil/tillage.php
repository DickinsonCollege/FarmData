<?php session_start(); ?>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_till:till_input" >
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
?>
<h3> Input Tillage Record</h3>
<br clear="all"/>
<label for='date'>Date:&nbsp; </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
<br clear="all"/>
<input type="hidden" name="hid" id="hid">
<script>
function show_confirm() {
   var hid = document.getElementById("hid");
   hid.value=numRows;
   var i = document.getElementById("month");
   var strUser3 = i.options[i.selectedIndex].text;
   var con="Tillage Date: "+strUser3+"-";
   var i = document.getElementById("day");
   var strUser3 = i.options[i.selectedIndex].text;
   con=con+strUser3+"-";
   var i = document.getElementById("year");
   var strUser3 = i.options[i.selectedIndex].text;
   con=con+strUser3+"\n";
   var i = document.getElementById("tractor");
   if(checkEmpty(i.value)) {
        alert("Please Enter tractor");
        return false;
   }
   strUser3 = i.options[i.selectedIndex].text;
   con=con + "Tractor: "+ strUser3+ "\n";
   var i = document.getElementById("implement");
   if(checkEmpty(i.value)) {
        alert("Please Enter implement");
        return false;
   }
   strUser3 = i.options[i.selectedIndex].text;
   con=con+"Implement: "+ strUser3+ "\n";

   var allfields = [];
   for (flds = 1; flds <= numRows; flds++) {
      var fld = document.getElementById("fieldID"+flds).value;
      if(checkEmpty(fld)) {
         alert("Please Enter Field in Row: " + flds);
         return false;
      }
      allfields[flds - 1] = fld;
      con=con+"\nFieldID: "+ fld + "\n";
      var perc = document.getElementById("perc"+flds).value;
      con=con+"Percent of Field Tilled: "+ perc + "\n";
      var pass = document.getElementById("passes"+flds).value;
      if(checkEmpty(pass)) {
         alert("Please Enter Passes in Row: " + flds);
         return false;
      }
      con=con+"Number of Passes: "+ pass + "\n";
      var mins = document.getElementById("minutes"+flds).value;
      if(checkEmpty(mins)) {
         alert("Please Enter Minutes in Row: " + flds);
         return false;
      }

      con=con+"Minutes in Field: "+ mins + "\n";
   }
   allfields.sort();
   for (i = 0; i < allfields.length - 1; i++) {
       if (allfields[i] == allfields[i + 1]) {
          alert("Error: same field entered twice!");
          return false;
       }
   }

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>
<label for="tractor"> Tractor: </label>
<div class="styled-select" id="tractor2">
<select name ="tractor" id="tractor" class="mobile-select">
<option value = 0 selected disabled> Tractor</option>
<?php 
$result=mysql_query("Select tractorName from tractor where active = 1");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[tractorName]\">$row1[tractorName]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="implement"> Implement: </label>
<div class="styled-select" id="implement2">
<select name ="implement" id="implement" class="mobile-select">
<option value = 0 selected disabled> Implement </option>

<?php 
$result=mysql_query("Select tool_name from tools");
while ($row1 =  mysql_fetch_array($result)){  
echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo '</select>';
echo '</div>';
?>

<br clear="all"/>
<br clear="all"/>
<table name="fieldTable" id="fieldTable">
<tr><th>FieldID</th><th>Number of Passes</th><th>% of Field Tilled</th><th>Minutes in Field</th><th> Comments?</th></tr>
</table>
<script type="text/javascript">
var numRows = 0;
function addRow() {
   numRows++;
   var table = document.getElementById("fieldTable");
   var row = table.insertRow(numRows);
   row.id = "row"+numRows;
   row.name = "row"+numRows;
   var cell0 = row.insertCell(0);
   cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+numRows+'"> <select name ="fieldID' + numRows +
     '" id="fieldID' + numRows + '" class="mobile-select">' +
     '<option value = 0 selected disabled> FieldID</option>' +
     '<?php 
     $result=mysql_query("Select fieldID from field_GH where active=1");
     while ($row1 =  mysql_fetch_array($result)){  
     echo "<option value = \"".$row1[fieldID]."\">".$row1[fieldID]."</option>";
     }
     ?>'
      + '</select></div>';
   var cell1 = row.insertCell(1);
   cell1.innerHTML = ' <div class="styled-select" id="passesDiv' + numRows + '"> <select name ="passes' +
     numRows + '" id="passes' + numRows + '" class="mobile-select">' +
     '<option value = 0 selected disabled> Passes </option>' +
     '<?php
         $cons=5;
         while ($cons>0) {
            echo "<option value =\"$cons\">$cons</option>";
            $cons--;
         }
      ?>' + 
     '</select></div>';
   var cell2 = row.insertCell(2);
   cell2.innerHTML = '<div class="styled-select" id="percDiv' + numRows + '"> <select name ="perc' + numRows +
     '" id="perc' + numRows+'" class="mobile-select">' +
     '<?php
         $result= 10;
         while ($result <= 100) {
         echo "<option value= \"$result\">$result%</option>";
            $result= $result + 10;
         }
      ?>' +
      '</select></div>';
   var cell3 = row.insertCell(3);
   cell3.innerHTML=' <div class="styled-select" id="minutesDiv'+numRows+'"> <select name ="minutes'+numRows +
     '" id="minutes'+numRows+'" class="mobile-select">' +
     '<option value = 0 disabled selected > Minutes </option>' +
     '<?php
        $cons=1;
        while ($cons<=300) {
           echo "<option value =\"$cons\">$cons</option>";
           $cons= $cons+ 1;
        }
      ?>' +
      '</select></div>';
   var cell4 = row.insertCell(4);
   cell4.innerHTML='<div id="comDiv'+numRows + '"><input type="checkbox" id = "com'+numRows+
       '" name = "com'+numRows+'"/ class="large-checkbox"></div>';

}
addRow();
function removeRow() {
   if (numRows > 0) {
      var field=document.getElementById('fieldID' + numRows);
      field.parentNode.removeChild(field);
      var passes=document.getElementById('passes' + numRows);
      passes.parentNode.removeChild(passes);
      var perc=document.getElementById('perc' + numRows);
      perc.parentNode.removeChild(perc);
      var minutes=document.getElementById('minutes' + numRows);
      minutes.parentNode.removeChild(minutes);
      var com=document.getElementById('com' + numRows);
      com.parentNode.removeChild(com);
      var table = document.getElementById("fieldTable");
      table.deleteRow(numRows);
      numRows--;
   }
}
</script>
<br clear="all"/>
<input type="button" id="addField" name="addField" class="genericbutton" onClick="addRow();" 
    value="Add Field">
<input type="button" id="removeField" name="removeField" class="genericbutton" onClick="removeRow();"
    value="Remove Field">
<br clear="all"/>
<br clear="all"/>
<div>
<label for="comments"><b>Comments:</b></label>
<br clear="all"/>
<textarea name ="comments"
rows="10" cols="30">
</textarea>
</div>
<br clear="all"/>
<input type="submit" class="submitbutton" name="submit" value="Submit" onclick= "return show_confirm();">
<?php
if(!empty($_POST['submit'])) {
   $numRows = $_POST['hid'];
   $comSanitized=escapehtml($_POST['comments']);
   $tractor=escapehtml($_POST['tractor']);
   $implement=escapehtml($_POST['implement']);
   $success = true;
   for ($i = 1; $i <= $numRows; $i++) {
      $com = $comSanitized;
      if (empty($_POST['com'.$i])) {
         $com = '';
      }
      $fieldID=escapehtml($_POST['fieldID'.$i]);
      $passes=escapehtml($_POST['passes'.$i]);
      $minutes=escapehtml($_POST['minutes'.$i]);
      $percent=escapehtml($_POST['perc'.$i]);
echo      $sql = "Insert into tillage(tractorName, fieldID,tilldate, tool,num_passes,comment,minutes,percent_filled) values('".
      $tractor."','".$fieldID."','".$_POST['year']."-".$_POST['month']."-".
      $_POST['day']."','".$implement."',".$passes.",'".$com."',".
      $minutes.",".$percent.");";
      $result = mysql_query($sql);
      if (!$result) {
         $success = false;
         echo "<script>alert(\"Error: ".mysql_error()."\");</script> \n";
      }
   }
   if($success){
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>
</form>
<form method="POST" action = "tillageReport.php?tab=soil:soil_fert:soil_till:till_report"><input type="submit" class="submitbutton" value = "View Table"></form>
