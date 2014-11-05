<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" method="post" action="<?php $_PHP_SELF ?>">
<h1><b>Add New Crop</b></h1>
<label for="crop">Crop Name:&nbsp;</label> 
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="name" id="name">
<br clear="all"/>
<label for="default">Harvest Unit:&nbsp;</label> 
<div id='crop'class='styled-select'>
<select name="default_unit" id="default_unit">
<option value=0 selected>Unit </option>
<?php
$sql = "select unit from extUnits";
$result=mysql_query($sql);
while ($row1 =  mysql_fetch_array($result)){  echo "\n<option value= \"$row1[unit]\">$row1[unit]</option>";
}
?>
</select>
<script type="text/javascript">
function show_confirm() {
	var i = document.getElementById("name").value;
        if (checkEmpty(i)) {
           alert("Enter crop name!");
           return false;
        }
        var con="Crop: "+ i+ "\n";
	var i = document.getElementById("default_unit");
        var strUser3 = i.options[i.selectedIndex].value;
        if (checkEmpty(strUser3)) {
           alert("Select a unit!");
           return false;
        }
        var con=con+"Unit: "+ strUser3+ "\n";
        return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<br clear="all"/>
<input class="submitbutton" type="submit" name="done" value="Add" onclick="return show_confirm();">

<?php
if (isset($_POST['done'])) {
   $sql="insert into plant values (upper('".escapehtml($_POST['name'])."'),'".escapehtml($_POST['default_unit'])."', 1,'".escapehtml($_POST['default_unit'])."', 1)";
   $result=mysql_query($sql);
   if ($result) {
      echo "<script>showAlert(\"Added Crop Successfully!\");</script> \n";
   } else {
      echo "<script>alert(\"Could not add crop: Please try again!\\n".mysql_error()."\");</script> \n";
   }
}
?>
