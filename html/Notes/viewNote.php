<?php session_start();?>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/authentication.php';
include $_SERVER['DOCUMENT_ROOT'].'/design.php';
include $_SERVER['DOCUMENT_ROOT'].'/connection.php';

?>
<h1> <b> View Comments </b> </h1>
<form name="form" method="post" action="notesTable.php?tab=notes:notes_report">
<?php
echo "<label for='from'>From:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date.php';
echo '<br clear="all"/>';
echo "<label for='to'>To:&nbsp;</label>";
include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
echo '<br clear="all"/>';
echo '<br clear="all"/>';
echo '<input class="submitbutton" type="submit" name="submit" value="Submit">';
echo '</form/>';
?>
