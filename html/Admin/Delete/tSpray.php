<?php session_start(); ?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   $dbcon = mysql_connect('localhost', 'wahlst_usercheck', 'usercheckpass') or
       die ("Connect Failed! :".mysql_error());
   mysql_select_db('wahlst_users');
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   $result = mysql_query($sql);
   echo mysql_error();
   $useropts='';
   while ($row = mysql_fetch_array($result)) {
      $useropts.='<option value="'.$row['username'].'">'.$row['username'].'</option>';
   }
}
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

?>
<form name='form' class="pure-form pure-form-aligned" method='POST'>
<?php
$date = $_GET['date'];
$id    = $_GET['id'];
$crop = $_GET['crop'];
$year = substr($date, 0, 4);
$month = substr($date, 5, 2);
$day = substr($date, 8, 2);
$user = $_GET['user'];
$origYear = $_GET['year'];
$origMonth = $_GET['month'];
$origDay = $_GET['day'];
$tcurYear = $_GET['tyear'];
$tcurMonth = $_GET['tmonth'];
$tcurDay = $_GET['tday'];
$complete=$_GET['complete'];
$initials=$_GET['initials'];
?>
<center>
<h2>Edit Tractor Spray Record</h2>
</center>

<div class="pure-control-group">
<label for="date">Date:</label>
<?php 
echo '<select name="month" id="month">';
echo '<option value='.$month.' selected>'.date("F", mktime(0,0,0, $month,10)).' </option>';
for($mth = 1; $mth < 13; $mth++) {echo "\n<option value =\"$mth\">".date("F", mktime(0, 0, 0, $mth, 10))."</option>";
}
echo '</select>';
echo '<select name="day" id="day">';
echo '<option value='.$day.' selected>'.$day.' </option>';
for($day1 = 1; $day1 < 32; $day1++) {echo "\n<option value =\"$day1\">$day1</option>";
}
echo '</select>';
echo '<select name="year" id="year">';
echo '<option value='.$year.' selected>'.$year.'</option>';
for($yr = $year - 3; $yr < $year+5; $yr++) {echo "\n<option value =\"$yr\">$yr</option>";
}
echo '</select></div>';

echo '<div class="pure-control-group">';
echo '<label>User:</label> ';
echo '<select name="user" id="user">';
echo '<option value="'.$user.'" selected>'.$user.' </option>';
if ($farm == 'dfarm') {
   $sql = 'select username from users where active = 1';
   $sqldata = mysql_query($sql) or die("ERROR4");
   while ($row = mysql_fetch_array($sqldata)) {
      echo '<option value="'.$row['username'].'">'.$row['username'].' </option>';
   }
} else {
   echo $useropts;
}
echo '</select></div>';

?>
<div class="pure-control-group">
<label for="status">Status:</label>
<select class="mobile-select" id="status" name="status">
<?php
echo '<option value=1';
if ($complete == 1) {
   echo ' selected';
}
echo '>Completed</option>';
echo '<option value=0';
if ($complete == 0) {
   echo ' selected';
}
echo '>Queued</option>';
?>
</select>
</div>

<div class="pure-control-group">
<label for="initials">Initials:</label>
<?php
echo "<input class='textbox mobile-input ' type='text' id='initials' name='initials' value='".
   $initials."'>";
?>
</div>
<br clear="all">
<br clear="all">
<table name="fieldTable" id="fieldTable" class="pure-table pure-table-bordered">
<thead><tr>
   <th>Field</th>
   <th>Num Beds Sprayed</th>
   <th>Acreage Sprayed</th>
   <th>Crop</th>
</tr></thead>
<?php
   $sql = "select * from tSprayField where id=".$id;
   $sqldata = mysql_query($sql);
   $numRows = 0;
   while($row=mysql_fetch_array($sqldata)){
      $numRows++;
      $result=mysql_query("Select fieldID from field_GH where active=1");
      $option='';
      while ($rowM =  mysql_fetch_array($result)){
         $option = $option. "<option value = \"".$rowM[fieldID]."\">".$rowM[fieldID]."</option>";
      }
      $numBedOptions ="";
      $sqlnumBeds="SELECT numberOfBeds as numberOfBeds FROM field_GH where fieldID='".
          $row['fieldID']."'";
      $crops = $row['crops'];
      $result=mysql_query($sqlnumBeds);
      $rowBeds=mysql_fetch_array($result); 
      $ind=1;
      while($ind<=$rowBeds['numberOfBeds']){
         $numBedOptions = $numBedOptions."<option value=\"".$ind."\">".$ind."</option> \n";
         $ind++;
      }

      echo  '<tr><td><center><div class="styled-select" id="fieldDiv'.$numRows.'"> <select name ="field'.$numRows.'" class="wide" id="field'.$numRows.'" onChange="addInput('.$numRows.'); addAcre('.$numRows.'); calculateTotalUpdate(); calculateWater();"><option value='.$row[fieldID].'>'.$row[fieldID].'</option>'.$option.'</select></div></center></td>';

      echo "<td><center><div id=\"maxBed".$numRows."\" class='styled-select2'> <select id=\"maxBed2".$numRows."\" name=\"maxBed2".$numRows."\"  onChange=\"addAcre(".$numRows."); calculateTotalUpdate(); calculateWater(); \" class='wide'><option value=\"".$row[numOfBed]."\">".$row[numOfBed]."</option>".$numBedOptions."</select></div></center></td>";

      echo "<td><center><div id=\"acreDiv".$numRows."\"><input class='wide' size = '4' type=\"text\" id=\"acre".$numRows."\" value=0 readonly></div> </center></td>";   
      echo "<td><center><input type = 'text' name = 'crop".$numRows."' class = 'wide' size = '40'  value ='".$crops."'></center></td></tr>";
   }
   echo "<input type='hidden' value='".$numRows."' name='numRows' id='numRows'>";
?>

</table>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" value="Add Field" class="submitbutton pure-button wide"  name="Add Field Spray" onclick="addRow()"/>
</div>
<div class="pure-u-1-2">
<input type="button" value="Remove Field" class="submitbutton pure-button wide"  name="Remove Field Spray" onclick="removeRow(); calculateWater();calculateTotalUpdate()"/>
</div>
</div>
<br clear="all"/>
<br clear="all"/>
<table name="materialTable" id="materialTable" class="pure-table pure-table-bordered">
<thead><tr>
   <th>Material Sprayed</th>
   <th>Rate (in units per acre)</th>
   <th>Unit</th>
   <th>Suggested Total Material</th>
   <th>Actual Total Material</th>
</tr></thead>
<?php
   $sql = "select * from tSprayWater where id=".$id;
   $sqldata = mysql_query($sql);
   $numRowsMat = 0;
   while($row=mysql_fetch_array($sqldata)){
      $numRowsMat++;
      $materialSprayed = "";
      $sqlM="SELECT sprayMaterial FROM tSprayMaterials";
      $resultM=mysql_query($sqlM);
      while($rowM=mysql_fetch_array($resultM)){
         $materialSprayed = $materialSprayed."<option value='".$rowM[sprayMaterial]."'>".$rowM[sprayMaterial]."</option>";
      };
      
      $sql="SELECT TRateMin, TRateMax, TRateDefault,(TRateMax-TRateMin)/10 AS dif FROM tSprayMaterials  where sprayMaterial='".$row['material']."'";
      $result=mysql_query($sql);
      $rateOptions = "";
      while ($rowM=mysql_fetch_array($result)) {

         $ind = $rowM['TRateMin'];
         $rateOptions = $rateOptions."<option value=".$rowM['TRateDefault'].">".$rowM['TRateDefault']."</option> \n";
         while($ind<=$rowM['TRateMax']){
            $rateOptions = $rateOptions."<option value=\"".$ind."\">".$ind."</option> \n";
            $formatDif=number_format($rowM['dif'],2,'.','');
            $ind=$ind + $formatDif;
         }
      }
      echo  "<tr><td><center><div id =\"material".$numRowsMat."\" class='styled-select2'><select class='wide' id=\"material2".$numRowsMat."\" name=\"material2".$numRowsMat."\"  onChange=\"addInputRates(".$numRowsMat."); calculateSuggested(".$numRowsMat."); addUnit(".$numRowsMat.");  \"\n>"."<option value=".$row[material].">".$row[material]."</option>\n".$materialSprayed."</select></div></center></td>";
      echo  "<td><center><div id =\"rate".$numRowsMat.
            "\" class='wide'><select class='wide' id='rate2".$numRowsMat.
            "' name='rate2".$numRowsMat."'  onChange=\"calculateSuggested(".
            $numRowsMat.");\"><option value=".$row[rate].">".$row[rate]."</option>".$rateOptions."</select></div></center></div></td>";
      echo  "<td><div id=\"unitDiv".$numRowsMat."\"><label style=\"font-size:12pt\" id='unit".$numRowsMat."'>Unit</label></div></td>";
      echo  "<td><center><div id=\"calculatedTotalDiv".$numRowsMat."\"><input type=\"text\" id=\"calculatedTotal".$numRowsMat."\" class='wide' value=0 readonly></div></center></td>";
      echo  "<td><center><div id=\"actualTotalDiv".$numRowsMat."\"><input class='wide' type=\"text\" id=\"actuarialTotal".$numRowsMat."\" name=\"actuarialTotal".$numRowsMat."\" value=".$row[actualTotalAmount]."></div></center></td></tr>";
   }
   echo "<input type='hidden' value='".$numRowsMat."' name='numRowsMat' id='numRowsMat'/>";
?>

</table>
<br clear="all"/>
<div class="pure-g">
<div class="pure-u-1-2">
<input type="button" value="Add Material" class="submitbutton pure-button wide" name="Add Material Spray" onclick="addRowMat()"/>
</div>
<div class="pure-u-1-2">
<input type="button" value="Remove Material" class="submitbutton pure-button wide" name="Delete Material Spray" onclick="removeRowMat()"/>
</div>
</div>
<br clear="all"/>
<table class='pure-table pure-table-bordered'>
<thead><tr>
   <th>Water (Gallons) Used Per Acre</th>
   <th>Total Gallons of Water Used </th>

</tr></thead>
<?php
$sql = "select * from tSprayMaster where id=".$id;
$sqldata = mysql_query($sql) or die (mysql_error());
$row = mysql_fetch_array($sqldata);
$water = $row['waterPerAcre'];
$crops = $row['crops'];
$comment = $row['comment'];
echo "<tr><td><center><input class='wide' type='text' name='waterPerAcre' id='waterPerAcre' value=".$water."  onkeyup='calculateWater();'></center></td>";
?>
<td><center><input type="text" class='wide' name="totalWater" id="totalWater" value=0 ></center></td></tr>
</table>
<br clear="all"/>

<!--
<tr><td><center><div id="cropGroup" class='styled-select2'><select class='styled-select' name="cropGroup2" id="cropGroup2"  >
<option value='<?php echo $crop;?>' ><?php echo $crop;?></option>
<?php 
$sqlG="SELECT * FROM cropGroupReference";
$resultG=mysql_query($sqlG);
while($rowG=mysql_fetch_array($resultG)){

echo "<option value=\"".$rowG['cropGroup']."\">".$rowG['cropGroup']."</option>\n";
}
?>
</select></div></center></td></tr>
-->

<br clear="all"/>
<div class="pure-control-group">
<label>Reason For Spray & Comments:</label>
<textarea  name="textarea" rows="5" cols="30"><?php echo $comment;?>
</textarea>
</div>
<br clear="all"/>
<br clear="all"/>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Delete/rowsMatfunc.php';
$count=1;
while( $count <= $numRows){
   echo "<script type='text/javascript'> addAcre(".$count."); calculateTotalUpdate(); calculateWater();</script>";
   $count++;
}
$count=1;
while( $count <= $numRowsMat){
   echo "<script type='text/javascript'>calculateSuggested(".$count.");addUnit(".$count.");</script>";
   $count++;
}

echo "<script type='text/javascript'>calculateWater(); </script>";
?>
<input type="submit" value = 'Submit' class='submitbutton pure-button wide' name="submit" onclick="return show_confirm();  ">
<?php
// pass values back through on post
echo '<input type="hidden" name = "numField" id="numField">';
echo '<input type="hidden" name = "numMaterial" id="numMaterial" >';
?>

</form>

<?php
if(!empty($_POST['submit'])) {
$comSanitized=escapehtml($_POST['textarea']);
$waterPerAcre=escapehtml($_POST['waterPerAcre']);
$username=escapehtml($_POST['user']);
$numField = escapehtml($_POST['numRows']);
$numMaterial = escapehtml($_POST['numRowsMat']);
$complete = $_POST['status'];
$initials = escapehtml($_POST['initials']);
$sqlM="update tSprayMaster SET sprayDate='".$_POST['year']."-".$_POST['month'].
   "-".$_POST['day']."',noField=".$numField.",noMaterial=".$numMaterial.
   ",waterPerAcre=".$waterPerAcre.", comment= '".
   $comSanitized."', user='".$username. "', complete = ".$complete.
   ", initials = '".$initials."' where id=".$id;
$rusultM=mysql_query($sqlM);
echo mysql_error();

$fieldInd=1;
// delete current rows in tSprayField;
$sqlDelete = "Delete from tSprayField where id=".$id;
mysql_query($sqlDelete) or die(mysql_error());
// add new Rows
while($fieldInd<= $_POST['numRows']){
   $field = escapehtml($_POST['field'.$fieldInd]);
   $bed = escapehtml($_POST['maxBed2'.$fieldInd]);
   $crops = escapehtml($_POST['crop'.$fieldInd]);
   $sqlF="INSERT INTO tSprayField VALUES(".$id." , '". $field."' , ".$bed.", '".$crops."');";
   mysql_query($sqlF) or die (mysql_error());
   $sqlF;
   echo mysql_error();
   $fieldInd++;
}


$materialInd=1;
$sqlDelete = "Delete from tSprayWater where id=".$id;
mysql_query($sqlDelete) or die (mysql_error());
while($materialInd<= $_POST['numRowsMat']){
   $material = escapehtml($_POST['material2'.$materialInd]);
   $rate = escapehtml($_POST['rate2'.$materialInd]);
   $total = escapehtml($_POST['actuarialTotal'.$materialInd]);
   $sqlW="INSERT INTO tSprayWater VALUES(".$id." , '". $material."', ".
      $rate." , ".$total."  );";
   mysql_query($sqlW) or die(mysql_error());
   $sqlW;
   echo mysql_error();
   $materialInd++;
}
}
if(!empty($_POST['submit'])) {
   echo "<script> showAlert('Edit Data Succesfully!'); </script>";

   echo '<meta http-equiv="refresh" content=0;URL="deleteTspray.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&crop='.$origCrop.
        '&tab=soil:soil_spray:tspray:tspray_edit>';
}

?>
<body id="soil">
</html>
