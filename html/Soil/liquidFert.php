<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<center>
<h2> Liquid Fertilizer Input </h2>
</center>
<form name='form' id='test' class='pure-form pure-form-aligned' method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_input">

<div class="pure-control-group">
<label for='date'> Fertilizer Application Date: </label>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>

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
   var i = document.getElementById("mat");
   if(checkEmpty(i.value)) {
        alert("Please Enter Material");
        return false;
   }
   strUser3 = i.options[i.selectedIndex].text;
   con=con + "Material: "+ strUser3+ "\n";
   var i = document.getElementById("quantity");
   if(checkEmpty(i.value)) {
        alert("Please Enter Quantity");
        return false;
   }
   con=con+"Total Quantity: "+ i.value;
   var i = document.getElementById("unit");
   console.log(i.value);
   if (checkEmpty(i.value)){
      alert("Please Enter unit");
      return false;
   }
   con = con + " "+ i.value + "\n";
   var allfields = [];
   for (flds = 1; flds <= numRows; flds++) {
      var fld = document.getElementById("fieldID"+flds).value;
      if(checkEmpty(fld)) {
         alert("Please Enter Field in Row: " + flds);
         return false;
      }
      allfields[flds - 1] = fld;
      con=con+"\nFieldID: "+ fld + "\n";
      var dripRows = document.getElementById("num_drip_rows"+flds).value;
      if(checkEmpty(dripRows)) {
         alert("Please Enter number of drip rows: " + flds);
         return false;
      }
      con=con+"Number of drip rows: "+ dripRows + "\n";

   }
   allfields.sort();
   for (i = 0; i < allfields.length - 1; i++) {
       if (allfields[i] == allfields[i + 1]) {
          alert("Error: same field entered twice!");
          return false;
       }
   }
<?php
   if ($_SESSION['labor']) {
      echo 'var wk = document.getElementById("numW").value;
      if (checkEmpty(wk) || tme<=wk || !isFinite(wk)) {
         showError("Enter a valid number of workers!");
         return false;
      }
      con = con+"\nNumber of workers: " + wk + "\n";
      var tme = document.getElementById("time").value;
      var unit = document.getElementById("timeUnit").value;
      if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
         showError("Enter a valid number of " + unit + "!");
         return false;
      }
      con = con+"Number of " + unit + ": " + tme + "\n";';
   }
?>

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>




<div class="pure-control-group">
<label for="crop"> Material:  </label>
<select name ="mat" id="mat" class='mobile-select'>
<?php
   $result=$dbcon->query("Select fertilizerName from liquidFertilizerReference");
   while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
      echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
   }
?>
   </select>
</div>

<div class="pure-control-group">
<label for"quantity">Quantity: </label>
   <input type="text" size="6" class="textbox2 mobile-input-half single_table" name="quantity" id="quantity"> 

<select name="unit" id="unit" class='mobile-select-half single_table'>
   <option value="QUARTS" > QUARTS </option>
   <option value="GALLONS" > GALLONS</option>
</select></div>
<br clear="all"/>
<center>
<table name="fieldTable" id="fieldTable" class="pure-table pure-table-bordered" style="width:auto;">
   <thead><tr><th>FieldID</th><th>Number of drip rows</th></tr></thead>
  <tbody></tbody>
</table>        
</center>

<script type="text/javascript">
   var numRows=0;
   function addRow(){
      numRows++;
      var table = document.getElementById("fieldTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(numRows - 1);
      row.id="row"+numRows;
      row.name="row"+ numRows;
      var cell0 = row.insertCell(0);
      cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+ numRows+'"> <select name ="fieldID' + numRows +
           '" id="fieldID' + numRows + '" class="wide">' +
           '<option value = 0 selected disabled> FieldID</option>' +
           '<?php
           $result=$dbcon->query("Select fieldID from field_GH where active=1");
           while ($row1 =  $result->fetch(PDO::FETCH_ASSOC)){
              echo "<option value = \"".$row1[fieldID]."\">".$row1[fieldID]."</option>";
         }
           ?>'
         + '</select></div>';
   
      var cell1 = row.insertCell(1);
      cell1.innerHTML = '<div id="num_drip_rows_Div'+numRows+'"><input type="text" class="wide" name="num_drip_rows'+ numRows +'" id="num_drip_rows'+ numRows +'"/></div>'; 

   }
   addRow();
   
   function removeRow(){
      if (numRows >0){
         var field = document.getElementById('fieldID'+numRows);
         var fieldDiv = document.getElementById("fieldDiv"+numRows);
         fieldDiv.removeChild(field);
         var drip_rows = document.getElementById('num_drip_rows'+numRows);
         var drip_rowsDiv = document.getElementById("num_drip_rows_Div"+numRows);
         drip_rowsDiv.removeChild(drip_rows);
         var table = document.getElementById("fieldTable");
         table.deleteRow(numRows);   
         numRows--;
      }
   }
</script>
<br clear="all" />
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" id="addField" name="addField" class="genericbutton pure-button wide" onClick="addRow();" 
    value="Add Field">
</div>
<div class="pure-u-1-2">
<input type="button" id="removeField" name="removeField" class="genericbutton pure-button wide" onClick="removeRow();"
    value="Remove Field">
</div>
</div>
<br clear="all"/>

<?php
if ($_SESSION['labor']) {
   echo '
   <div class="pure-control-group">
   <label for="numWorkers">Number of workers (optional):</label>
   <input onkeypress= \'stopSubmitOnEnter(event)\'; type = "text" value = 1 name="numW" id="numW" class="textbox2mobile-input single_table">
   </div>

   <div class="pure-control-group">
   <label>Enter time in Hours or Minutes:</label>
   <input onkeypress=\'stopSubmitOnEnter(event);stopTimer();\' type="text" name="time" id="time"
   class="textbox2 mobile-input-half single_table" value="1">
   <select name="timeUnit" id="timeUnit" class=\'mobile-select-half single_table\' onchange="stopTimer();">
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
   </select>
   </div> ';

include $_SERVER['DOCUMENT_ROOT'].'/timer.php';
}
?>

<div class="pure-control-group">
<label for="comments"> Comments: </label>
<textarea name="comments" id="comments"
cols=30 rows=5>
</textarea>
</div>
<br clear="all"><br>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="submit" class="submitbutton pure-button wide" value="Submit" name="submit" onclick="return show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "liquidFertReport.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report"><input type="submit" class="submitbutton pure-button wide" value = "View Table"
onclick="return confirmLeave();">
</form>
</div>
</div>
<?php
if (isset($_POST['submit'])) {
   $numRows = $_POST['hid'];
   $sum = 0;
   $totRows = 0;
   // find the sum of the area = sum length i * numDripRowsi
   for ($i=1; $i <= $numRows; $i++){
      $fieldID = escapehtml($_POST['fieldID'.$i]);
      $num_drip_rows = escapehtml($_POST['num_drip_rows'.$i]);
      $result = $dbcon->query("select length from field_GH where fieldID='".$fieldID."'");
      $row1 =  $result->fetch(PDO::FETCH_ASSOC);
      $length=$row1['length'];
      $sum = $sum + $length * $num_drip_rows;
      $totRows += $num_drip_rows;
   }
   
   $comments = escapehtml($_POST['comments']);
   $username = escapehtml($_SESSION['username']);
   $mat = escapehtml($_POST['mat']);
   $totalQuantity = escapehtml($_POST['quantity']);
   $unit = escapehtml($_POST['unit']);
   if ($_SESSION['labor']) {
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
   } else {
      $totalHours = 0;
   }
   $sql="Insert into liquid_fertilizer(username,inputDate,fieldID, fertilizer, quantity, dripRows, ".
      "unit, comments, hours) values ('".
      $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."', :fieldID,'".$mat.
      "', :quantity, :num_drip_rows, '".$unit."', '".$comments."', :hours)";
   try {
      $stmt = $dbcon->prepare($sql);
      for ( $i=1; $i <= $numRows; $i++) {
         $fieldID = escapehtml($_POST['fieldID'.$i]);
         $num_drip_rows = escapehtml($_POST['num_drip_rows'.$i]);
         $result = $dbcon->query("select length from field_GH where fieldID='".$fieldID."'");      
         $row1 =  $result->fetch(PDO::FETCH_ASSOC);
         $length=$row1['length'];
         $quantity = ($length*$num_drip_rows/$sum)*$totalQuantity;
         $stmt->bindParam(':fieldID', $fieldID, PDO::PARAM_STR);
         $stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
         $stmt->bindParam(':num_drip_rows', $num_drip_rows, PDO::PARAM_INT);
         $hours = $totalHours * $num_drip_rows / $totRows;
         $stmt->bindParam(':hours', $hours, PDO::PARAM_STR);
         $stmt->execute();
/*
      $sql="Insert into liquid_fertilizer(username,inputDate,fieldID, fertilizer, quantity, dripRows, unit, comments
) values ('".
      $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$mat.
      "', ".$quantity.",".$num_drip_rows.",'".$unit."', '".$comments."')";
*/
      }
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   

   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>


