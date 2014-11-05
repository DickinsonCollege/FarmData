<?php session_start(); ?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<h3 >Compost Application Form</h3>
<form name='form' method='post' action="<?php $_PHP_SELF ?>?tab=soil:soil_fert:soil_compost:compost_input">
<script>
        function show_confirm() {
        var i = document.getElementById("month");
        var strUser3 = i.options[i.selectedIndex].text;
        var con="Date Utilized On: "+strUser3+"-";
        var i = document.getElementById("day");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"-";
        var i = document.getElementById("year");
        var strUser3 = i.options[i.selectedIndex].text;
        var con=con+strUser3+"\n";
        var i = document.getElementById("fieldID");
        var strUser3 = i.value;
        if(checkEmpty(strUser3)) {
        alert("Please Select a Field");
        return false;
        }
        var con=con+"FieldID: "+ strUser3+ "\n";
        var i = document.getElementById("percent").value;
        if(checkEmpty(i)) {
        alert("Please Input The Percent of Field Spread");
        return false;
        }
        var con=con+"Percent of Field Spread: "+ i+ "\n";
        var i = document.getElementById("pileID").value;
        if(checkEmpty(i)) {
        alert("Please Input The Compost Pile ID");
        return false;
        }
        var con=con+"Compost Pile ID: "+ i+ "\n";
        var i = document.getElementById("tperload").value;
        if(checkEmpty(i) || i <= 0 || isNaN(i)) {
        alert("Please Input a Valid Number for The Tons Per Load");
        return false;
        }
        var con=con+"Tons per Load: "+ i+ "\n";
        var i = document.getElementById("numloads").value;
        if(checkEmpty(i) || i <= 0 || isNaN(i)) {
        alert("Please Input a Valid Number for  The Number of Loads");
        return false;
        }
        var con=con+"Number of Loads: "+ i+ "\n";
        var i = document.getElementById("incorp_tool").value;
        if(checkEmpty(i)) {
        alert("Please Input The Incorporation Tool");
        return false;
        }
        var con=con+"Incorporation Tool: "+ i+ "\n";
        var i = document.getElementById("incorpTiming").value;
        if(checkEmpty(i)) {
        alert("Please Input The Incorporation Timing");
        return false;
        }
        var con=con+"Incorporation Timing: "+ i+ "\n";

        return confirm("Confirm Entry:"+"\n"+con);
        }
</script>

<br clear="all"/>
<label for="Seed">Date:</label>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
?>

<br clear="all"/>
<label for="fieldID"> Field ID: </label>
<div class="styled-select" id="field">
<select name ="fieldID" id="fieldID" class='mobile-select'>
<option value = 0 selected disabled> FieldID</option>
<?php
$result=mysql_query("Select fieldID from field_GH where active=1");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= '".$row1[fieldID]."'>".$row1[fieldID]."</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="percent"> Percent of Field Spread:&nbsp; </label>
<div class="styled-select" id="field">
<select name ="percent" id="percent" class='mobile-select'>
<?php
$result= 10;
while ($result <= 100) {
echo "\n<option value= \"$result\">$result</option>";
$result= $result + 10;
}
echo '</select>';
echo '</div>'
?>
<br clear="all"/>

<label for="pile"> Compost Pile ID:&nbsp; </label>
<div class="styled-select">
<select name ="pileID" id="pileID" class='mobile-select'>
<option value = 0 selected disabled> Pile ID</option>
<?php
$result=mysql_query("Select pileID from compost_pile where active=1");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= '".$row1['pileID']."'>".$row1['pileID']."</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="pile"> Tons per Load:&nbsp; </label>
<input onkeypress="stopSubmitOnEnter(event)" id ="tperload"name="tperload" type="text" class="textbox2 mobile-input" value=0 >
<br clear="all"/>
<label for="pile"> Number of Loads: &nbsp; </label>
<input id ="numloads"name="numloads" onkeypress= 'stopSubmitOnEnter(event)'; type="text" class="textbox2 mobile-input" value=0 >
<br clear="all"/>
<label for="incorp">Incorporation Tool: &nbsp;</label>
<div class="styled-select" id="incorp_toolis">
<select name ="incorp_tool" id="incorp_tool" class='mobile-select'>
<option value = 0 selected disabled> Incorporation Tool </option>
<?php
$result=mysql_query("Select tool_name from tools");
while ($row1 =  mysql_fetch_array($result)){
echo "\n<option value= \"$row1[tool_name]\">$row1[tool_name]</option>";
}
echo '</select>';
echo '</div>';
?>
<br clear="all"/>
<label for="incorpTime">Incorporation Timing: &nbsp;</label>
<div class="styled-select">
<select name="incorpTiming" id="incorpTiming" class='mobile-select'>
<option value = 0 selected disabled> Incorporation Timing </option>
<option value = "Immediate"> Immediate </option>
<option value = "Same Day"> Same Day </option>
<option value = "Next Day"> Next Day </option>
<option value = "Not Incorporated"> Not Incorporated </option>
</select>
</div>
<br clear="all"/>
<label for="comments"> Comments: </label>
<br clear="all"/>
<textarea name="comments" rows="10" cols="30">
</textarea>
<br clear="all"/>
<br clear="all"/>
<input onclick="return show_confirm();"type="submit" class = "submitbutton" name="submit" id="submit" value="Submit">
<br clear="all"/>
</form>
<form method="POST" action = "/Soil/compostReport.php?tab=soil:soil_fert:soil_compost:compost_report"><input type="submit" class="submitbutton" value = "View Table"></form>
<?php
if (isset($_POST['submit'])) {
   $comments = escapehtml($_POST['comments']);
   $tperload = escapehtml($_POST['tperload']);
   $numloads = escapehtml($_POST['numloads']);
   $fieldID = escapehtml($_POST['fieldID']);
   $pileID = escapehtml($_POST['pileID']);
   $incorpTiming = escapehtml($_POST['incorpTiming']);
   $incorp_tool = escapehtml($_POST['incorp_tool']);
   $percent = escapehtml($_POST['percent']);
   $sql="Insert into utilized_on(util_date,fieldID,incorpTool,pileID,tperacre,incorpTiming,fieldSpread,comments) values ('".
     $_POST['year']."-".$_POST['month']."-".$_POST['day']."','".
     $fieldID."','".$incorp_tool."','".$pileID.  "',((".$tperload.
     "*".$numloads.")/((".$percent."/100) * (Select size from field_GH where fieldID='".$fieldID.
        "'))),'".$incorpTiming."', ((".$percent.
        "/100)*(Select size from field_GH where fieldID='".
        $fieldID."')), '".$comments."')";
   $result= mysql_query($sql);
   if(!$result) { 
      echo "<script> alert(\"Could not enter Compost Data! Try again.\\n ".mysql_error()."\"); </script>";
   }else {
      echo "<script> showAlert(\"Compost Record Entered Successfully\"); </script>";
   }
}
?>
