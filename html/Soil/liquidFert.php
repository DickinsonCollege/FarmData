<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3> Liquid Fertilizer Input </h3>
<br clear="all"/>
<form name='form' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'];?>?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_input">
<label for='date'> Fertilizer Application Date: </label>
<br clear="all"/>


<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>

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

   return confirm("Confirm Entry:"+"\n"+con);
}
</script>


<br clear="all"/> 

<label for="crop"> Material: &nbsp; </label>
<div class="styled-select" id="mat2">
	<select name ="mat" id="mat" class='mobile-select'>
	<option value = 0 selected disabled> Material </option>
	<?php
		$result=mysql_query("Select fertilizerName from liquidFertilizerReference");
		while ($row1 =  mysql_fetch_array($result)){
   		echo "\n<option value= \"$row1[fertilizerName]\">$row1[fertilizerName]</option>";
		}
	?>
	</select>
</div>
<br clear="all"/>
<label for"quantity">Quantity:&nbsp; </label>
	<?php
	if ($_SESSION['mobile']) echo "<br clear='all'>";
	?>
	<input type="text" class="textbox2 mobile-input-half single_table" name="quantity" id="quantity"> 
<div class="styled-select" id="unit2">
<select name="unit" id="unit" class='mobile-select-half single_table'>
	<option value=0 selected disabled> Unit </option>
	<option value="QUARTS" > QUARTS </option>
	<option value="GALLONS" > GALLONS</option>
</select></div>
<br clear="all">
<br>
<table name="fieldTable" id="fieldTable">
	<tr><th>FieldID</th><th>Number of drip rows</th></tr>
</table>        

<script type="text/javascript">
	var numRows=0;
	function addRow(){
		numRows++;
		var table = document.getElementById("fieldTable");
		var row = table.insertRow(numRows);
		row.id="row"+numRows;
		row.name="row"+ numRows;
		var cell0 = row.insertCell(0);
		cell0.innerHTML = '<div class="styled-select" id="fieldDiv'+ numRows+'"> <select name ="fieldID' + numRows +
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
		cell1.innerHTML = '<div id="num_drip_rows_Div'+numRows+'"><input type="text" class="textbox25 mobile-input inside_table" name="num_drip_rows'+ numRows +'" id="num_drip_rows'+ numRows +'"/></div>'; 

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
<input type="button" id="addField" name="addField" class="genericbutton" onClick="addRow();" 
    value="Add Field">
<input type="button" id="removeField" name="removeField" class="genericbutton" onClick="removeRow();"
    value="Remove Field">
<br clear="all"/>


<br clear="all">

<label for="comments"> Comments: </label>
<br clear="all">
<textarea name="comments" id="comments"
cols=30 rows=10>
</textarea>
<br clear="all"><br>
<input type="submit" class="submitbutton" value="Submit" name="submit" onclick="return show_confirm();">
</form>
<form method="POST" action = "liquidFertReport.php?tab=soil:soil_fert:soil_fertilizer:liquid_fertilizer:liquid_fertilizer_report"><input type="submit" class="submitbutton" value = "View Table">
</form>
<?php
if (isset($_POST['submit'])) {
	$numRows = $_POST['hid'];
	$sum = 0;
	// find the sum of the area = sum length i * numDripRowsi
	for ($i=1; $i <= $numRows; $i++){
		$fieldID = escapehtml($_POST['fieldID'.$i]);
		$num_drip_rows = escapehtml($_POST['num_drip_rows'.$i]);
		$result = mysql_query("select length from field_GH where fieldID='".$fieldID."'");
                echo mysql_error();
		$row1 =  mysql_fetch_array($result);
                $length=$row1['length'];
		$sum = $sum + $length * $num_drip_rows;
	}
	
   $comments = escapehtml($_POST['comments']);
   $username = escapehtml($_SESSION['username']);
   //$fieldID = escapehtml($_POST['fieldID']);
   $mat = escapehtml($_POST['mat']);
	$totalQuantity = escapehtml($_POST['quantity']);
   $unit     = escapehtml($_POST['unit']);
	//$crop = escapehtml($_POST['crop']);
   //echo "<script>addInput3();</script>";
   $success = true;
	for ( $i=1; $i <= $numRows; $i++) {
		$fieldID = escapehtml($_POST['fieldID'.$i]);
		$num_drip_rows = escapehtml($_POST['num_drip_rows'.$i]);
		$result = mysql_query("select length from field_GH where fieldID='".$fieldID."'");		
                echo mysql_error();
		$row1 =  mysql_fetch_array($result);
                $length=$row1['length'];
		$quantity = ($length*$num_drip_rows/$sum)*$totalQuantity;
		$sql="Insert into liquid_fertilizer(username,inputDate,fieldID, fertilizer, quantity, dripRows, unit, comments
) values ('".
      $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$mat.
      "', ".$quantity.",".$num_drip_rows.",'".$unit."', '".$comments."')";
		$result = mysql_query($sql) or die(mysql_error());
		if (!$result) {
      $success = false;
			echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
		}
	}
	//$sql="Insert into liquid_fertilizer(username,inputDate,fieldID, fertilizer, quantity, num_drip_rows, numBeds, totalApply, comments) values ('".
     // $username."','".$_POST['year']."-".$_POST['month']."-".$_POST['day']."','".$fieldID."','".$mat.
     // "', '".$crop."',".$_POST['rate'].",".$_POST['beds'].",".$_POST['pounds'] * $_POST['beds'].
      //",'".$comments."')";
	
   //$result = mysql_query($sql) or die(mysql_error());
   if ($success) {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>


