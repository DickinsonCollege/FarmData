<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];
$year=$_GET['year'];
$month=$_GET['month'];
$day=$_GET['day'];
$detail=$_GET['detail'];
$date=$year."-".$month."-".$day;
$deleteCrop=escapehtml($_GET['crop']);
$currentID = $_GET['currentID'];
include $_SERVER['DOCUMENT_ROOT'].'/Admin/Sales/convert.php';

if ($ind = array_search('Loss', $targs)) {
  unset($targs[$ind]);
  $targs = array_values($targs);
}
?>

<center>
<h2>Create/Edit Harvest List</h2>
</center>

<form name='form' class='pure-form pure-form-aligned' method='POST' action="<?php echo $_SERVER['PHP_SELF'].
  '?tab=admin:admin_add:admin_harvestlist&year='.$year.'&month='.$month.'&day='.$day.'&currentID='.
  $currentID.'&detail='.$detail; ?>">

<script type="text/javascript">
	function checkIfOnList(){
	var crop=encodeURIComponent(document.getElementById('crop').value);
	var id=<?php echo $currentID; ?>;
	xmlhttp= new XMLHttpRequest();
        xmlhttp.open("GET", "checkInList.php?crop="+crop+"&id="+id, false);
	xmlhttp.send();
	var responseVar=xmlhttp.responseText;
        <?php
        for ($i = 0; $i < count($targs); $i++) {
            echo 'document.getElementById("'.str_replace(" ", "_",$targs[$i]).'").value=0;';
        }
        ?>
	if(responseVar){	
          var items = JSON.parse(responseVar);    
           for (var targ in items) {
              document.getElementById(targ).value = items[targ];
	   }
           addall();
        }
}
</script>

<table class="pure-table pure-table-bordered">
<thead>
<tr>	<th>Crop</th>
	<th>Field</th>
<?php
for ($i = 0; $i < count($targs); $i++) {
   echo "<th>".$targs[$i]."</th>";
}
?>
        <th>Total</th>
</tr>
</thead>
<tbody>
<tr>
	<td> 
<div class="styled-select">
<select name= "crop" id="crop" class="mobile-select" onChange="addInput();checkIfOnList();" >
<!--
<option value=0 selected  > Crop </option>
-->
<?php
$result = $dbcon->query("SELECT  crop from plant");
while ($row1 = $result->fetch(PDO::FETCH_ASSOC)){
 echo "\n<option value= \"".$row1[crop]."\">".$row1[crop]."</option>";}
?>
</select>
</div>
</td>

<td><center><input class="textbox4 mobile-input inside_table" type= "text" name="fieldID" id="fieldID" size="10"></center></td>
 
<script type="text/javascript">
   function addInput(){
        var crop = encodeURIComponent(document.getElementById('crop').value);
        var collection = document.getElementsByClassName("target-unit");
	xmlhttp= new XMLHttpRequest();
	xmlhttp.open("GET", "/Harvest/hupdate.php?crop="+crop, false);
	xmlhttp.send();

	for (i = 0; i < collection.length; i++) {
	   collection[i].innerHTML=xmlhttp.responseText;
	}
}  

	function addall(){
          sum = 0;
        <?php
          for ($i = 0; $i < count($targs); $i++) {
	     echo 'var unit = document.getElementsByName("unit'.$i.'")[0].value;';
	     echo 'var number = document.getElementById("'.str_replace(' ', '_', $targs[$i]).'").value;'; 
	     echo 'var crop = document.getElementById("crop").value;';
	     echo 'sum += number / conversion[crop][unit];';  
             echo "\n";
          }
        ?>
	document.getElementById('total').value = sum;
	document.getElementById('default_unit').innerHTML = '&nbsp;&nbsp;' + default_unit[crop];
   }	
	
</script>
<?php
for ($i = 0; $i < count($targs); $i++) {
   echo '<td><input size="3" type= "text"';
   echo ' name="'.str_replace(" ", "_",$targs[$i]).'" id="'.str_replace(" ", "_",$targs[$i]).
       '" value=0 oninput="addall();">';
   echo '&nbsp;&nbsp;<select name="unit'.$i.'" id="'.str_replace(" ", "_",$targs[$i]).'_unit" class="target-unit" onchange = "addall();">';
   echo '<option value=0 selected> Units </option>';
   echo '</select></td>';

}
?>
<td><input class=" textbox4 mobile-input inside_table" type="text" name="total" id="total" size="3" readonly><span id = 'default_unit'></span></td>
</tr>
</tbody>
</table>
<br clear="all"/>
<input type="submit" name="form" class="submitbutton pure-button wide" value="Submit" > 
</form>
<br clear="all"/>

<?php
if(isset($_POST['form'])&& isset($_POST['crop'])&& isset($_POST['fieldID'])){
   $crop= escapehtml($_POST['crop']);
   $fieldID=escapehtml($_POST['fieldID']);
   $sql = "delete from harvestListItem where crop='".$crop."' and id =".$currentID;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   for ($i = 0; $i < count($targs); $i++) {
/*
echo "<script>console.log(\"".$targs[$i]."\");</script>";
echo "<script>console.log(\"".$_POST[$targs[$i]]."\");</script>";
*/
     
   if (isset($_POST[str_replace(" ", "_",$targs[$i])]) && $_POST[str_replace(" ", "_",$targs[$i])] > 0) {
	$unit = escapehtml($_POST['unit'.$i]);
      $sql = "insert into harvestListItem VALUES(".$currentID.", '".$crop.
          "', ".$_POST[str_replace(" ", "_",$targs[$i])].", '".$unit."', '".$fieldID."', '".
          $targs[$i]."')"; 
      try {
         $stmt = $dbcon->prepare($sql);
         $stmt->execute();
      } catch (PDOException $p) {
         phpAlert('Could not enter data', $p);
         die();
      }
   //echo "<script>console.log(\"".$sql."\");</script>";
   } 
  }   
}
?>


<?php
if($deleteCrop&&$deleteCrop!=$crop){
   $sql="DELETE FROM harvestListItem where id=$currentID and crop='".
      $deleteCrop."'";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('Could not enter data', $p);
      die();
   }
}
?>

<center>
<h3> Harvest List For: <?php echo $date ?></h3>
</center>
<table class="pure-table pure-table-bordered">
<thead>
<tr>
        <th>Crop </th>
        <th>Field</th>
       <!-- <th>Units </th>-->
        <?php
        for ($i = 0; $i < count($targs); $i++) {
            echo '<th>'.$targs[$i].'</th>';
        }
        ?>
        <th>Total</th>
	<th>Delete</th>
</tr>
</thead>
<tbody>


<?php
$sql = "select * from harvestListItem where id=".$currentID;
$result = $dbcon->query($sql);
$tabArr = array();
while($row=$result->fetch(PDO::FETCH_ASSOC)){ 
   $tabArr[$row['crop']][$row['target']]=$row['amt'];
   $tabArr[$row['crop']]['fieldID']=$row['fieldID'];
   $tabArr[$row['crop']][$row['target'].'_units']=$row['units'];
}
foreach ($tabArr as $crp=>$arr) {
   echo "<tr><td>".$crp."</td><td>".$tabArr[$crp]['fieldID']."</td>";
   //echo $tabArr[$crp]['units']."</td>";
   $tot = 0;
   $reqs = array();
   $reqs_unit = array();
   for ($i = 0; $i < count($targs); $i++) {
      echo "<td>";
      if (isset($tabArr[$crp][$targs[$i]])) {
         $val = $tabArr[$crp][$targs[$i]];
	 $val_unit = $tabArr[$crp][$targs[$i].'_units'];
      } else {
         $val = 0;
      }

      if ($val == 0) {
	echo $val.'&nbsp;'.$default_unit[$crp];
      }
      else {
	echo $val.'&nbsp;'.$val_unit.'(S)';
        $tot += $val / $conversion[$crp][$val_unit];
      }
      echo "</td>";
   }
   echo "<td>".number_format((float) $tot,2,'.','')."&nbsp&nbsp".$default_unit[$crp]."(S)"."</td>";
   echo "<td><form method=\"POST\" action=\"harvestListAdmin.php?tab=admin:admin_add:admin_harvestlist&crop=".
      $crp."&date=".$date."&year=".$year."&month=".$month."&day=".$day.
      "&currentID=".$currentID."\">";
   echo "<input type=\"submit\" class=\"deletebutton pure-button wide\" value=\"Delete\"></form> </td>";

   echo "</tr>";
}
echo "<tbody>";
echo "</table>";
?>
<br clear="all"/>

<form name='comment' method='POST'>
<center>
<h3>Add Notes</h3>
<?php
echo "<textarea name=\"comments\" rows=\"10\" cols=\"50\" class='mobile-comments'>";
$sqlGetValue="SELECT comment from harvestList where id=".$currentID;

if(isset($_POST['submit'])){
   $comSanitized=escapehtml($_POST['comments']);
   $sql="UPDATE harvestList SET comment='".$comSanitized."' where id=".$currentID;
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('Could not enter data', $p);
      die();
   }
}
$res = $dbcon->query($sqlGetValue);
$row2=$res->fetch(PDO::FETCH_ASSOC);
echo $row2['comment'];
?>
</textarea>
</center>
<br clear="all"/>
<br clear="all"/>
<input type="submit" name="submit" class = "submitbutton pure-button wide" value="Update Notes" >
</form>

</body>
</html>

