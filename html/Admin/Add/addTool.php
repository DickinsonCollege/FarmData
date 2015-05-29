<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<form name="form" class="pure-form pure-form-aligned" method="post" action="<?php $_PHP_SELF ?>">
<center>
<h2>Add New Tool/Implement</h2>
</center>

<div class="pure-control-group">
<label for="name"> Tool/Implement Name:</label>
<input class="textbox3 mobile-input" onkeypress= 'stopSubmitOnEnter(event)'; 
  type="text" name="name" id="name">
</div>

<div class="pure-control-group">
<label for="type">Incorporation Tool?: </label>
<select name="type" id="type" class='mobile-select'>
<option selected value="0">No</option>
<option value="1">Yes</option>
</select>
</div>

<script>
function show_confirm() {
   var i = document.getElementById("name").value;
   var con="Tool/Implement Name: "+ i+ "\n";
   var t = document.getElementById("type").value;
   var n = "OTHER";
   if (t == 1) {
      n = "INCORPORATION";
   }
   con = con + "Tool Type: " + n + "\n";
   return confirm("Confirm Entry: " +"\n"+con);
}
</script>
<br clear="all"/>
<input onclick= "return show_confirm()";  class="submitbutton pure-button wide" type="submit" name="done" value="Add">
<?php
if (!empty($_POST['done'])) {
   if(!empty($_POST['name'])) {
      if ($_POST['type'] == 0) {
         $type = "OTHER";
      } else {
         $type = "INCORPORATION";
      }
      $sql="Insert into tools(tool_name, type) values ('".
         escapehtml(strtoupper($_POST['name']))."', '".$type."')";
      $result=mysql_query($sql);
      if (!$result) {
         echo "<script>alert(\"Could not enter data: Please try again!\\n".
            mysql_error()."\");</script>\n";
      } else {
         echo "<script>showAlert(\"Entered data successfully!\");</script> \n";
      }
   } else {
      echo "<script>alert(\"Enter all data!\\n".mysql_error()."\");</script> \n";
   }
}
?>

