<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name='form' class='pure-form pure-form-aligned' id='form'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_scout:soil_weed:weed_input">

<center>
<h2> Weed Scouting Input Form </h2>
</center>

<div class="pure-control-group">
<label for='date'> Date: </label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

<input type="hidden" name="hid" id="hid">
<!--
<label style="float:left;" for="species"> Add New Weed Species</label>
-->
<br clear="all"/>
<br clear="all" />
<table name="fieldTable" id="fieldTable" class="pure-table pure-table-bordered">
<thead>
<tr><th>Field Name</th><th>Weed Species</th><th>infestation</th><th>% to seed</th></tr>   
</thead>
<tbody>
</tbody>
</table>
<script type="text/javascript">
var numRows=0;
function addRow(){
   numRows++;
   var table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];
   var row = table.insertRow(-1);
   row.id = "row" + numRows;
   row.name = "row" + numRows;
   var cell0 = row.insertCell(0);
   cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+numRows+'"> <select name="fieldID'+numRows+
                     '" id="fieldID'+numRows+'" class="wide">'+
                     '<option value= 0 selected disabled> Field Name </option>'+
                     '<?php
                         $result=$dbcon->query("select fieldID from field_GH where active=1");
                        while($row1 = $result->fetch(PDO::FETCH_ASSOC)){
                           echo '<option value="'.$row1[fieldID].'">'.$row1[fieldID].'</option>';
                        }
                      ?>' + '</select></div>';
   var cell1 = row.insertCell(1);
   cell1.innerHTML = '<div class="styled-select" id="speciesDiv'+numRows+'"> <select class="wide" name="species'+numRows+'" id="species'+numRows+'">'+
                     '<option value = 0 selected disabled>weed species</option>'+
                     '<?php
                        $sql = 'Select weedName from weed';
                        $result = $dbcon->query($sql);
                        while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
                           echo '<option value="'.$row1['weedName'].'">'.$row1['weedName'].'</option>';
                        }      
                      ?>' + '</select></div>';
   var cell2 = row.insertCell(2);
   cell2.innerHTML = '<div class="styled-select" id="infestDiv'+numRows+'"><select class="wide" name="infest'+numRows+'" id="infest'+numRows+'"><option value = 0 selected disabled> Infestation</option> <option>0</option> <option>1</option> <option>2</option> <option>3</option> </select></div>';
   var cell3 = row.insertCell(3);
   cell3.innerHTML = '<div class="styled-select" id="g2seedDiv'+numRows+'"><select class="wide" name="g2seed'+numRows+'" id="g2seed'+numRows+'"><option value = 0 selected disabled> %ToSeed</option> <option>0</option> <option>25</option> <option>50</option> <option>75</option> <option>100</option> </select></div>';

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
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input class = "submitbutton pure-button wide" type="button" id="add" value="Add Species" onclick="addRow();"/>
</div>
<div class="pure-u-1-2">
<input type="button" id="remove" class="submitbutton pure-button wide"  value="Remove Species" onClick="removeRow();"/>
</div>
</div>


<br clear="all"/>
<div class="pure-control-group">
<label for="comments"> Comments: </label>
<textarea name="comments" rows="5" cols="30" id="comments"></textarea>
</div>
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
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" name="submit" class="submitbutton pure-button wide" value="Submit" onClick="return show_confirm();">
<?php
echo "</form>";
echo "</div>";
echo '<div class="pure-u-1-2">';
echo '<form method="POST" action = "weedReport.php?tab=soil:soil_scout:soil_weed:weed_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>';
echo "</div>";
echo "</div>";
if (isset($_POST['submit'])) {
   $comments = escapehtml($_POST['comments']);
   $var= $_POST['hid'];
   $sql = "insert into weedScout(sDate, fieldID, weed, infestLevel, gonetoSeed, comments) values ('".
      $_POST['year']."-".$_POST['month']."-".$_POST['day']."', :fieldID, :species, :infest, :g2seed,'".
      $comments."')";
   try {
      $stmt = $dbcon->prepare($sql);
      while ($var>0) {
         $fieldID = escapehtml($_POST['fieldID'.$var]);  
         $infest = escapehtml($_POST['infest'.$var]);
         $g2seed = escapehtml($_POST['g2seed'.$var]);
         $species = escapehtml($_POST['species'.$var]);
   //      $sql = "insert into weedScout(sDate, fieldID, weed, infestLevel, gonetoSeed, comments) values ('".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$species."', ".$infest.", ".$g2seed.",'".$comments."')";
         $stmt->bindParam(':fieldID', $fieldID, PDO::PARAM_STR);
         $stmt->bindParam(':species', $species, PDO::PARAM_STR);
         $stmt->bindParam(':infest', $infest, PDO::PARAM_INT);
         $stmt->bindParam(':g2seed', $g2seed, PDO::PARAM_INT);
         $stmt->execute();
         $var--;
      }
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Data Entered Successfully!\\n\");</script>\n";
}
?>
