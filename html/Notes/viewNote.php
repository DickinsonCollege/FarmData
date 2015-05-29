<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

?>
<center><h2> <b> View Comments </b> </h2></center>
<form name="form" method="get" class ="pure-form pure-form-aligned" action="notesTable.php">
<input type = "hidden" name = "tab" value = "notes:notes_report">
<?php
echo "<div class = 'pure-control-group'><label for='from'>From:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo "<label for='to'>To:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/></div>';
echo '<br clear="all"/>';
echo '<input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit">';
echo '</form/>';
?>
</div>
