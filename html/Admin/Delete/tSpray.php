<?php session_start(); ?>
<?php 
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';

$farm = $_SESSION['db'];
if ($farm != 'dfarm') {
   try {
      $dbcon = new PDO('mysql:host=localhost;dbname=wahlst_users', 'wahlst_usercheck', 'usercheckpass',
         array(PDO::MYSQL_ATTR_INIT_COMMAND => 'set sql_mode="TRADITIONAL"'));
      $dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
      die("Connect Failed! :".$e->getMessage());
   }
   $sql="select username from users where dbase='".$_SESSION['db']."'";
   try {
      $result = $dbcon->query($sql);
   } catch (PDOException $p) {
      die($p->getMessage());
   }
   $useropts='';
   while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
   $sqldata = $dbcon->query($sql);
   while ($row = $sqldata->fetch(PDO::FETCH_ASSOC)) {
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
   $sqldata = $dbcon->query($sql);
   $numRows = 0;
   while($row = $sqldata->fetch(PDO::FETCH_ASSOC)){
      $numRows++;
      $result = $dbcon->query("Select fieldID from field_GH where active=1");
      $option='';
      while ($rowM = $result->fetch(PDO::FETCH_ASSOC)){
         $option = $option. "<option value = \"".$rowM[fieldID]."\">".$rowM[fieldID]."</option>";
      }
      $numBedOptions ="";
      $sqlnumBeds="SELECT numberOfBeds as numberOfBeds FROM field_GH where fieldID='".
          $row['fieldID']."'";
      $crops = $row['crops'];
      $result=$dbcon->query($sqlnumBeds);
      $rowBeds=$result->fetch(PDO::FETCH_ASSOC); 
      $ind=1;
      while($ind<=$rowBeds['numberOfBeds']){
         $numBedOptions = $numBedOptions."<option value=\"".$ind."\">".$ind."</option> \n";
         $ind++;
      }

      echo  '<tr><td><center><div class="styled-select" id="fieldDiv'.$numRows.'"> <select name ="field'.$numRows.'" class="wide" id="field'.$numRows.'" onChange="addInput('.$numRows.'); addAcre('.$numRows.'); calculateTotalUpdate(); calculateWater();"><option value="'.$row[fieldID].'">'.$row[fieldID].'</option>'.$option.'</select></div></center></td>';

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
   $sqldata = $dbcon->query($sql);
   $numRowsMat = 0;
   while($row=$sqldata->fetch(PDO::FETCH_ASSOC)){
      $numRowsMat++;
      $materialSprayed = "";
      $sqlM="SELECT sprayMaterial FROM tSprayMaterials";
      $resultM=$dbcon->query($sqlM);
      while($rowM=$resultM->fetch(PDO::FETCH_ASSOC)){
         $materialSprayed = $materialSprayed."<option value='".$rowM[sprayMaterial]."'>".$rowM[sprayMaterial]."</option>";
      };
      
    $sql="SELECT TRateMin, TRateMax, TRateDefault,(TRateMax-TRateMin)/10 AS dif FROM tSprayMaterials  where sprayMaterial='".$row['material']."'";
      $result=$dbcon->query($sql);
      $rateOptions = "";
      while ($rowM=$result->fetch(PDO::FETCH_ASSOC)) {

         $ind = $rowM['TRateMin'];
         $rateOptions = $rateOptions."<option value=".$rowM['TRateDefault'].">".$rowM['TRateDefault']."</option> \n";
         $formatDif=number_format($rowM['dif'],2,'.','');
         if ($formatDif < 0.1) {
            $formatDif = 0.1;
         }
         while($ind<=$rowM['TRateMax']){
            $rateOptions = $rateOptions."<option value=\"".$ind."\">".$ind."</option> \n";
            $ind=$ind + $formatDif;
         }
      }
      echo  "<tr><td><center><div id =\"material".$numRowsMat."\" class='styled-select2'><select class='wide' id=\"material2".$numRowsMat."\" name=\"material2".$numRowsMat."\"  onChange=\"addInputRates(".$numRowsMat."); calculateSuggested(".$numRowsMat."); addUnit(".$numRowsMat.");  \"\n>"."<option value='".$row['material']."'>".$row['material']."</option>\n".$materialSprayed."</select></div></center></td>";
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
$sqldata = $dbcon->query($sql);
$row = $sqldata->fetch(PDO::FETCH_ASSOC);
$water = $row['waterPerAcre'];
$crops = $row['crops'];
$comment = $row['comment'];
echo "<tr><td><center><input class='wide' type='text' name='waterPerAcre' id='waterPerAcre' value=".$water."  onkeyup='calculateWater();'></center></td>";
?>
<td><center><input type="text" class='wide' name="totalWater" id="totalWater" value=0 ></center></td></tr>
</table>
<br clear="all"/>
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
try {
   $stmt = $dbcon->prepare($sqlM);
   $stmt->execute();
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}

$fieldInd=1;
// delete current rows in tSprayField;
$sqlDelete = "Delete from tSprayField where id=".$id;
try {
   $stmt = $dbcon->prepare($sqlDelete);
   $stmt->execute();
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}
// add new Rows
$sqlF="INSERT INTO tSprayField VALUES(".$id." , :field, :bed, :crops);";
try {
   $stmt = $dbcon->prepare($sqlF);
   while($fieldInd<= $_POST['numRows']){
      $field = escapehtml($_POST['field'.$fieldInd]);
      $bed = escapehtml($_POST['maxBed2'.$fieldInd]);
      $crops = escapehtml($_POST['crop'.$fieldInd]);
   //   $sqlF="INSERT INTO tSprayField VALUES(".$id." , '". $field."' , ".$bed.", '".$crops."');";
      $stmt->bindParam(':field', $field, PDO::PARAM_STR);
      $stmt->bindParam(':bed', $bed, PDO::PARAM_STR);
      $stmt->bindParam(':crops', $crops, PDO::PARAM_STR);
      $stmt->execute();
      $fieldInd++;
   }
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}


$materialInd=1;
$sqlDelete = "Delete from tSprayWater where id=".$id;
try {
   $stmt = $dbcon->prepare($sqlDelete);
   $stmt->execute();
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}
$sqlW="INSERT INTO tSprayWater VALUES(".$id." , :material, :rate, :total);";
try {
   $stmt = $dbcon->prepare($sqlW);
   while($materialInd<= $_POST['numRowsMat']){
      $material = escapehtml($_POST['material2'.$materialInd]);
      $rate = escapehtml($_POST['rate2'.$materialInd]);
      $total = escapehtml($_POST['actuarialTotal'.$materialInd]);
//      $sqlW="INSERT INTO tSprayWater VALUES(".$id." , '". $material."', ".
//         $rate." , ".$total."  );";
      $stmt->bindParam(':material', $material, PDO::PARAM_STR);
      $stmt->bindParam(':rate', $rate, PDO::PARAM_STR);
      $stmt->bindParam(':total', $total, PDO::PARAM_STR);
      $stmt->execute();
      $materialInd++;
   }
} catch (PDOException $p) {
   phpAlert('', $p);
   die();
}
}
if(!empty($_POST['submit'])) {
   echo "<script> showAlert('Edited Tractor Spray Record Succesfully!'); </script>";

   echo '<meta http-equiv="refresh" content=0;URL="deleteTspray.php?year='.$origYear.'&month='.$origMonth.
        '&day='.$origDay.'&tyear='.$tcurYear.'&tmonth='.$tcurMonth.'&tday='.$tcurDay.'&crop='.$origCrop.
        '&tab=soil:soil_spray:tspray:tspray_edit>';
}

?>
<body id="soil">
</html>
