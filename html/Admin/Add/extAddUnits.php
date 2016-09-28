<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="add">
<form name='form' class="pure-form pure-form-aligned" method='post' action='<?php $_SERVER['PHP_SELF'] ?>'>
<center>
<h2>Add New Harvest Unit</h2>
</center>

<div class="pure-control-group">
<label for="unit">New Unit Name:</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="unit" id="unit">
</div>
<script>
function show_confirm() {
   var i = document.getElementById("unit").value;
   if (checkEmpty(i)) {
       alert("Enter a unit name!");
       return false;
   }
   var con="Unit: "+ i+ "\n";
   return confirm("Confirm Entry:"+"\n"+con);
}
</script>
<br clear="all"/>
<input class="submitbutton pure-button wide" type="submit" name="add" value="Add" onclick = "return show_confirm();">

<?php
if (isset($_POST['add'])) {
   $sql="insert into extUnits(unit) values ('".escapehtml(strtoupper($_POST['unit']))."')";
   try {
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
   } catch (PDOException $p) {
      phpAlert('', $p);
      die();
   }
   echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
}
?>

</form>
