<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
$farm = $_SESSION['db'];
$currentID=$_GET['currentID'];
$year=$_GET['year'];
$month=$_GET['month'];
$day=$_GET['day'];
$detail=$_GET['detail'];
$date=$year."-".$month."-".$day;
$deleteCrop=escapehtml($_GET['crop']);
$sql = "select * from targets where active = 1 order by targetName";
$targs = array();
$result = mysql_query($sql);
echo mysql_error();
while ($row = mysql_fetch_array($result)) {
   // $targs[] = str_replace(" ", "_", $row['targetName']);
   $targ = $row['targetName'];
   if ($targ != 'Loss') {
     $targs[] = $targ;
   }
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
//	console.log(id);
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
	<th>Units</th>
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
<option value=0 selected  > Crop </option>

<?php
$result = mysql_query("SELECT  crop from plant");
while ($row1 =  mysql_fetch_array($result)){
 echo "\n<option value= \"".$row1[crop]."\">".$row1[crop]."</option>";}
?>
</select>
</div>
</td>

<td>
<div class="styled-select">
<select name="units" id="units" class="mobile-select">
<option value=0 selected> Units </option>
</select> 
</div>
</td>

<td><center><input class="textbox4 mobile-input inside_table" type= "text" name="fieldID" id="fieldID" size="10"></center></td>

<script type="text/javascript">
	 function addInput(){
        var crop = encodeURIComponent(document.getElementById('crop').value);
        var newdiv=document.getElementById("units");
	xmlhttp= new XMLHttpRequest();
	xmlhttp.open("GET", "/Harvest/hupdate.php?crop="+crop, false);
	xmlhttp.send();

	newdiv.innerHTML="<select name= 'units' id= 'units'>"+xmlhttp.responseText+"</select>";
	}

	function addall(){
          sum = 0;
        <?php
          for ($i = 0; $i < count($targs); $i++) {
              echo 'sum += parseFloat(document.getElementById("'.str_replace(" ", "_",$targs[$i]).
                   '").value);';
              echo "\n";
          }
        ?>
	document.getElementById('total').value = sum;
	
	}	
	
</script>
<?php
for ($i = 0; $i < count($targs); $i++) {
   echo '<td><input size="3" class="wide" type= "text"';
   echo ' name="'.str_replace(" ", "_",$targs[$i]).'" id="'.str_replace(" ", "_",$targs[$i]).
       '" value=0 oninput="addall();"></td>';

}
?>
<td><input class=" textbox4 mobile-input inside_table" type="text" name="total" id="total" size="3" readonly></td>
</tr>
</tbody>
</table>
<br clear="all"/>
<input type="submit" name="form" class="submitbutton pure-button wide" value="Submit" > 
</form>
<br clear="all"/>

<?php
if(isset($_POST['form'])&& isset($_POST['crop'])&& isset($_POST['fieldID']) &&
     isset($_POST['units'])){
   $crop= escapehtml($_POST['crop']);
   $fieldID=escapehtml($_POST['fieldID']);
   $units=escapehtml($_POST['units']);
   mysql_query("delete from harvestListItem where crop='".$crop."' and id =".$currentID);
echo mysql_error();
  for ($i = 0; $i < count($targs); $i++) {
echo "<script>console.log(\"".$targs[$i]."\");</script>";
echo "<script>console.log(\"".$_POST[$targs[$i]]."\");</script>";
     if (isset($_POST[str_replace(" ", "_",$targs[$i])]) && $_POST[str_replace(" ", "_",$targs[$i])] > 0) {
       $sql = "insert into harvestListItem VALUES(".$currentID.", '".$crop.
          "', ".$_POST[str_replace(" ", "_",$targs[$i])].", '".$units."', '".$fieldID."', '".
          $targs[$i]."')"; 
echo "<script>console.log(\"".$sql."\");</script>";
       $res = mysql_query($sql);
       if (!$res){
         echo "<script type=\"text/javascript\"> alert('Could not enter data: please try again!\n"+
          mysql_error()+"');</script>";
       }
     } 
  }   
}
?>


<?php
if($deleteCrop&&$deleteCrop!=$crop){
   $sql="DELETE FROM harvestListItem where id=$currentID and crop='".
      $deleteCrop."'";
   mysql_query($sql);
   echo mysql_error();
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
        <th>Units </th>
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
$result = mysql_query($sql);
echo mysql_error();
$tabArr = array();
while($row=mysql_fetch_array($result)){ 
   $tabArr[$row['crop']][$row['target']]=$row['amt'];
   $tabArr[$row['crop']]['fieldID']=$row['fieldID'];
   $tabArr[$row['crop']]['units']=$row['units'];
}
foreach ($tabArr as $crp=>$arr) {
   echo "<tr><td>".$crp."</td><td>".$tabArr[$crp]['fieldID']."</td><td>";
   echo $tabArr[$crp]['units']."</td>";
   $tot = 0;
   for ($i = 0; $i < count($targs); $i++) {
      echo "<td>";
      if (isset($tabArr[$crp][$targs[$i]])) {
         $val = $tabArr[$crp][$targs[$i]];
      } else {
         $val = 0;
      }
      echo $val;
      $tot += $val;
      echo "</td>";
   }
   echo "<td>".$tot."</td>";
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
   mysql_query($sql);
   echo mysql_error();
}
$row2=mysql_fetch_array( mysql_query($sqlGetValue));
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

