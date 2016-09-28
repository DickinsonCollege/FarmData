<?php session_start();?>
<?php
//if ($_SESSION['mobile']) {
    echo "<html>";
//}
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
$currentCrop=$_GET['crop'];
$currentDate=$_GET['date'];
$dateArray = explode("-", $currentDate);
$farm = $_SESSION['db'];
if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])) {
   $dDay = $_POST['day'];
   $dMonth = $_POST['month'];
   $dYear = $_POST['year'];
} else if (isset($_GET['day']) && isset($_GET['month']) && isset($_GET['year'])) {
   $dDay = $_GET['day'];
   $dMonth = $_GET['month'];
   $dYear = $_GET['year'];
} 
?>

<script type="text/javascript">
var farm = "<?php echo $farm;?>";
function show_confirm() {
   var undef;
   var cb = document.getElementById("cropButton");
   var crp = cb.value;
   if(checkEmpty(crp) || crp == "Crop") {
      showError("Please Select a Crop");
      return false;
   }
   var con="Crop: "+ crp + "<br>";
   var m = document.getElementById("month").value;
   con=con+"Date of Harvest: "+m+"-";
   var d = document.getElementById("day").value;
   con=con+d+"-";
   var y = document.getElementById("year").value;
   con=con+y+"<p>";

   var numRows = document.getElementById("numRows").value;
   if (numRows < 1) {
      showError("Add at Least One Field!");
      return false;
   }
   for (j = 1; j <= numRows; j++) {
      var fld = document.getElementById("fieldID"+j).value;
      if(checkEmpty(fld)) {
         showError("Please Select a Field Name in row " + j);
         return false;
      }
      con=con+"Name of Field: "+ fld+ "<br>";
      var yld = document.getElementById("yield"+j).value;
      if (yld == undef || yld == "" || yld<<?php
         if ($farm != 'wahlst_spiralpath') {
            echo "=";
         }?>0 || !isFinite(yld) ) {
        showError("Please enter a valid yield in row " + j);
        return false;
      }
      con=con+"Yield: "+ yld+ "<br>";
      var unit = document.getElementById("unit"+j).value;
      if(checkEmpty(unit)) {
         showError("Please Select a Unit in row " + j);
         return false;
      }
      con=con+"Unit: "+ unit + "<br>";
   <?php
   //include $_SERVER['DOCUMENT_ROOT'].'/Seeding/checkGen.php';
   if ($_SESSION['gens']) {
      echo 'var gen = document.getElementById("gen" +j).value;';
      echo 'con += "Succession #: " + gen + "<br>";';
   }
   if ($_SESSION['labor']) {
      echo 'var numW = document.getElementById("numW"+j).value;
      if (checkEmpty(numW) || numW<=0 || !isFinite(numW)) {
         showError("Please enter a valid number of workers in row " + j);
         return false;
      }
     con=con+"Number of workers: "+ numW+ "<br>";

     var tme = document.getElementById("time"+j).value;
     var unit = document.getElementById("timeUnit").value;
      if (checkEmpty(tme) || tme<=0 || !isFinite(tme)) {
         showError("Please enter a valid number of " + unit + " in row "+j);
         return false;
      }
      con = con+"Number of " + unit + ": " + tme + "</p><p>";';
   } else {
      echo 'con +="<br>";';
   }
   ?>
   }

   var msg = "Confirm Entry:"+"<br>"+con;
   showConfirm(msg, 'test');
}
</script>

<form name='form' class='pure-form pure-form-aligned' id='test'  method='POST' action="<?php echo $_SERVER['PHP_SELF'].'?year='.$dYear.
  '&month='.$dMonth.'&day='.$dDay.'&crop='.$currentCrop.'&currentID='.$_GET['currentID'].
  '&tab=harvest:harvestInput&date='.$currentDate;?>" >

<center>
<h2 class='form_header'>Harvest Input Form</h2>
</center>
<fieldset>
<div class='pure-control-group'>
<label class='input_label' for="crop">Date of Harvest:</label>
<?php
// if (!$_SESSION['mobile']) echo "<br clear='all'>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>
</div>
<div class='pure-control-group'>
<label class='input_label' for='cropButton'>Crop:</label>
<select name='cropButton' id='cropButton' class='mobile-select' onchange="clearTable();"> 
<?php
$sql = "SELECT crop FROM plant WHERE active=1";
$result = $dbcon->query($sql);
   // echo "<option value=0 disabled>Crop</option>";
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   echo "<option value='".$row['crop']."'>".$row['crop']."</option>";
}
?>
</select></div>

<?php
if($_SESSION['mobile']){
echo "<br clear=\"all\">";
}
?>
<br clear="all"/>
<!--
 style="table-layout:fixed;"
-->
<table id='harvestTable' name='harvestTable'
  class='pure-table pure-table-bordered'>
<!--
<col/>
<col/>
<?php
if ($_SESSION['mobile']) {
   echo '<col width="130px"/>';
} else {
   echo '<col width="240px"/>';
}
?>
<?php
if ($_SESSION['gens']) {
   echo '<col>';
}
if ($_SESSION['labor']) {
   echo '<col/>';
   if ($_SESSION['mobile']) {
      echo '<col width="130px"/>';
   } else {
      echo '<col width="240px"/>';
   }
}
?>
-->

<thead><tr><th>Name of Field</th><th>Yield</th><th>&nbsp;&nbsp;Unit&nbsp;&nbsp;</th>
<?php
if ($_SESSION['gens']) {
   echo '<th>Succ&nbsp;#</th>';
}
if ($_SESSION['labor']) {
  echo '
<th>Workers</th><th>
<select name="timeUnit" id="timeUnit" class="mobile-select wide">
   <option value="minutes">Minutes</option>
   <option value="hours">Hours</option>
</select>
</th></tr></thead>';
}
?>
<tbody></tbody>
</table>
<br clear="all"/>
<script type="text/javascript">
window.onload=function() {
   var wid = window.innerWidth || document.body.clientWidth;
   var min = 750;
   // console.log(wid);
   // console.log(min);
   if (wid < min) {
      document.getElementById("harvestTable").style.width=min;
   }
      // console.log(document.getElementById("harvestTable").style.width);

}
</script>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" id="addField" name="addField" 
  class="genericbutton pure-button wide" onClick="addRow();"
value="Add Field">
</div>
<div class="pure-u-1-2">
<input type="button" id="removeField" name="removeField" 
  class="genericbutton pure-button wide" onClick="removeRow();"
value="Remove Field">
</div>
</div>
<br clear="all"/>
<input type="hidden" name="numRows" id="numRows" value=0>
<script type="text/javascript">
function getGen(j) {
   var year = document.getElementById("year").value;
   var crop = encodeURIComponent(document.getElementById("cropButton").value);
   var fldID = encodeURIComponent(document.getElementById("fieldID" + j).value);
   xmlhttp= new XMLHttpRequest();
   xmlhttp.open("GET", "update_Gen.php?crop="+crop+"&plantyear="+year + "&fieldID=" + fldID, false);
   xmlhttp.send();
   var cell25 = document.getElementById('genCell' + j);
   cell25.innerHTML=" <select name='gen" + j + "' id=\'gen" +j +
        "' class='wide'>" + xmlhttp.responseText + " </select>";
}

var numRows = 0;
function addRow() {
   var cb = document.getElementById("cropButton");
   if (cb.value=="0") {
      showError("Error: choose crop first!");
   } else {
      var year = document.getElementById("year").value;
      var crop = encodeURIComponent(cb.value);
      xmlhttp= new XMLHttpRequest();
      xmlhttp.open("GET", "update_field.php?crop="+crop+"&plantyear="+year, false);
      xmlhttp.send();
      if(xmlhttp.responseText=="\n") {
          showError("Error: no " + cb.value + " planted in " + year + "!");
          return;
      }
      numRows++;
      var table = document.getElementById("harvestTable").getElementsByTagName('tbody')[0];
      var row = table.insertRow(numRows - 1);
      row.id = "row"+numRows;
      row.name = "row"+numRows;
      var cell0 = row.insertCell(0);
      var genStr = "<div class='styled-select' id ='fieldID2" + numRows + "''>  <select name= 'fieldID" +
        numRows + "' id= 'fieldID" + numRows + "' ";
      <?php
      if ($_SESSION['gens']) {
          echo "genStr += \"onchange='getGen(\" + numRows + \");' \";";
      }
      ?>
      genStr += "class='wide'>"+xmlhttp.responseText+"</select> </div>";
      cell0.innerHTML = genStr;
      var cell1 = row.insertCell(1);
      var yld = "";
      if (farm == 'wahlst_spiralpath' && numRows > 1) {
         yld = "0";
      }
      cell1.innerHTML="<input onkeypress= 'stopSubmitOnEnter(event);' type = 'text' name='yield"+numRows+
         "' id='yield"+numRows+"' class='textbox mobile-input inside_table wide' size='5' value = '" +
         yld + "'>";
      <?php
      if ($farm == 'wahlst_spiralpath') {
        echo 'xmlhttp.open("GET", "hupdatesp.php?crop="+crop, false);';
      } else {
        echo 'xmlhttp.open("GET", "hupdate.php?crop="+crop, false);';
      }
      ?>
      xmlhttp.send();
      var cell2 = row.insertCell(2);
      cell2.innerHTML="<div class = 'styled-select'> <select name='unit"+numRows+"' id='unit" + numRows +
        "' class='mobile-select wide'>"+xmlhttp.responseText+" </select> </div>";
      var col = 3;
<?php
if ($_SESSION['gens']) {
   echo "   var cell25 = row.insertCell(3);";
   echo "   cell25.id = 'genCell' + numRows;";
   echo "   col++;";
   echo '   cell25.innerHTML=" <select name=\'gen"+numRows+"\' id=\'gen" + numRows +
        "\' class=\'mobile-select wide\'></select>";';
}
if ($_SESSION['labor']) {
echo '
      var cell3 = row.insertCell(col);
      cell3.innerHTML="<input onkeypress= \'stopSubmitOnEnter(event);\' type=\'text\' name=\'numW"+
         numRows+ "\' id=\'numW" + numRows + "\' value=\"1\" " +
         "class=\'textbox mobile-input inside_table wide\' size = \'3\'>";
      col++;
      var cell4 = row.insertCell(col);
      cell4.innerHTML="<input onkeypress=\'stopSubmitOnEnter(event);\' type=\'text\' name=\'time"+
         numRows+ "\' id=\'time"+numRows+"\' value=\"1\" " +
         "class=\'textbox mobile-input inside_table wide\' size = \'5\'>";';
}
?>
   }
   var nr = document.getElementById("numRows");
   nr.value=numRows;
<?php
if ($_SESSION['gens']) {
   echo "   getGen(numRows);";
}
?>
}
   
function removeRow() {
   if (numRows > 0) {
      var field=document.getElementById('fieldID2' + numRows);
      field.parentNode.removeChild(field);
      var yield=document.getElementById('yield' + numRows);
      yield.parentNode.removeChild(yield);
      var unit=document.getElementById('unit' + numRows);
      unit.parentNode.removeChild(unit);
<?php
if ($_SESSION['labor']) {
  echo '    var time=document.getElementById(\'time\' + numRows);
      time.parentNode.removeChild(time);';
  echo '    var work=document.getElementById(\'numW\' + numRows);
      work.parentNode.removeChild(work);';
}
?>
      var table = document.getElementById("harvestTable");
      table.deleteRow(numRows);
      numRows--;
   }
/*
   if (numRows == 0) {
      var cb = document.getElementById("cropButton");
      cb.disabled=false;
   }
*/
   var nr = document.getElementById("numRows");
   nr.value=numRows;
}

function clearTable() {
   while (numRows > 0) {
      removeRow();
   }
}
</script>

<?php
if($currentDate){
  echo "<script type=\"text/javascript\">";
  echo "var eb = document.getElementById(\"cropButton\");";
  echo "eb.value = \"".html_entity_decode($currentCrop, ENT_QUOTES)."\";";
  echo "addRow();";
  echo "</script>";
}
?>


<!--
<br clear="all"/>
<div class='pure-control-group' id='genDiv'>
-->
<?php
/*
if ($_SESSION['gens']) {
   echo '<label for="gen">Succession #:</label> ';
   echo '<select id= "gen" name="gen" class="mobile-select">';
   echo '</select>';
   echo '</div>';
}
*/
?>
<div class='pure-control-group'>
<label class='input_label' for="comments">Comments:</label>
<textarea  name="comments" class='input_comments' rows="5" cols="30">
</textarea>
</div>

</fieldset>
<div class="pure-g">
<div class="pure-u-1-2">
<input  class="submitbutton pure-button wide" type="button" value="Submit" onclick= "show_confirm();">
</form>
</div>
<div class="pure-u-1-2">
<form method="POST" action = "harvestReport.php?tab=harvest:harvestReport"><input type="submit" class="submitbutton pure-button wide" value = "View Table" onclick="return confirmLeave();"></form>
</div>

<?php
   if(!empty($_GET['currentID'])){
   echo '<div class="pure-u-1">';
   echo "<form method='POST' action='harvestList.php?year=".$_GET['year']."&month=".$_GET['month'].
         "&day=".$_GET['day']."&currentID=".$_GET['currentID'].
         "&tab=harvest:harvestList&detail=0'>";
       echo "<input type='submit' class='submitbutton pure-button wide' value ='View Harvest List (".$currentDate.")'></form> ";
  echo '</div>';
}
?>
<!--
<br clear="all"/>
-->
</div>
<?php
// echo "</form>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $crop = escapehtml($_POST['cropButton']);
   $year = $_POST['year'];
   $month = $_POST['month'];
   $day = $_POST['day'];
   $numRows = $_POST['numRows'];
   $comments =escapehtml( $_POST['comments']);
   $unitSQL = "select units from plant where crop = '".$crop."'";
   try {
      $unitdata = $dbcon->query($unitSQL);
   } catch (PDOException $p) {
      phpAlert('', $p);
   }
   $row = $unitdata->fetch(PDO::FETCH_ASSOC);
   $insertUnit = $row['units'];
   for ($j = 1; $j <= $numRows; $j++) {
      $fieldID = escapehtml($_POST['fieldID'.$j]);
      $yield = escapehtml($_POST['yield'.$j]);
      $unit = escapehtml($_POST['unit'.$j]);

      if ($_SESSION['labor']) {
         // Check if given time is in minutes or hours
         $time = escapehtml($_POST['time'.$j]);
         if ($_POST['timeUnit'] == "minutes") {
            $hours = $time/60;
         } else if ($_POST['timeUnit'] == "hours") {
            $hours = $time;
         }
         // Check if num workers is filled in
         $numW = escapehtml($_POST['numW'.$j]);
         if ($numW != "") {
            $totalHours = $hours * $numW;
         } else {
            $totalHours = $hours;
         }
      } else {
         $hours=0;
         $totalHours=0;
      }

//      include $_SERVER['DOCUMENT_ROOT'].'/Seeding/setGen.php';
      if ($_SESSION['gens']) {
         $gen = $_POST['gen'.$j];
      } else {
         $gen = 1;
      }
      if ($farm == 'wahlst_spiralpath') {
        $sql = "INSERT INTO harvested(username,hardate,crop,fieldID,yield,hours, gen, comments, unit) VALUES('".
           $_SESSION['username']."','".$year.'-'.$month.'-'.$day."','".$crop."','".$fieldID.
           "',".$yield.",".$hours.", ".$gen.",'".$comments."','".$unit."')";
      } else {
         $sql = "INSERT INTO harvested(username,hardate,crop,fieldID,yield,hours,gen, comments, unit) VALUES('"
            .$_SESSION['username']."','".$year.'-'.$month.'-'.$day."','".$crop."','".$fieldID.
            "',$yield/(Select conversion from units where crop= '".$crop."' and unit= '".$unit.
            "'),".$totalHours.", ".$gen.",'".$comments."', '".$insertUnit."')";
      }
   try {
      $value = $dbcon->prepare($sql);
      $value->execute();
   } catch (PDOException $p) {
      phpAlert('Could not enter data', $p);
      die('fatal error');
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";

  }
}
?>
