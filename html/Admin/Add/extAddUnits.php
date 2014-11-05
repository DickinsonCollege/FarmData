<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="add">
<form name='form' method='post' action='<?php $_SERVER['PHP_SELF'] ?>'>
<h1><b>Add New Units For Harvesting</b></h1>

<label for="unit">New Unit Name:&nbsp;</label>
<input onkeypress= 'stopSubmitOnEnter(event)'; class="textbox3" type="text" name="unit" id="unit">
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
<br clear="all"/>
<input class="submitbutton" type="submit" name="add" value="Add" onclick = "return show_confirm();">

<?php
if (isset($_POST['add'])) {
   $sql="insert into extUnits(unit) values ('".escapehtml(strtoupper($_POST['unit']))."')";
   $result=mysql_query($sql);
   if (!$result) {
      echo "<script>alert(\"Could not enter data: Please try again!\\n".mysql_error()."\");</script>\n";
   }else {
      echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
   }
}
?>

</form>
