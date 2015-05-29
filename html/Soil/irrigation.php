<?php session_start(); ?>
<?php
// include $_SERVER['DOCUMENT_ROOT'].'/testPureMenu.php';
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';
include $_SERVER['DOCUMENT_ROOT'].'/stopSubmit.php';
?>
<body id="soil">
<form name='form' class='pure-form pure-form-aligned' method='get' action="irrigationReport.php?tab=soil:soil_irrigation:irrigation_report">
   <input type='hidden' name='tab' id='tab' value='soil:soil_irrigation:irrigation_report'>
   <center>
   <h2 class="hi"> Irrigation Report</h2>
   </center>
   <fieldset>
      <div class='pure-control-group'>
         <label for="from">From:</label>
         <?php
         include $_SERVER['DOCUMENT_ROOT'].'/date.php';
         ?>
      </div>
      <div class='pure-control-group'>
         <label for="to">To:</label>
         <?php
         include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
         ?>
      </div>

      <br clear="all"/>
         <input type="submit" class="submitbutton pure-button wide" name="submit" value="Submit">
</fieldset>
</form>
