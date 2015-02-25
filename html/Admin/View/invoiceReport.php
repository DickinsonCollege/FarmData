<?php session_start(); ?>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<h3 class="hi"> Select Invoice Records: </h3>
<br>

<form method="GET" action="invoiceGraph.php">
	<?PHP
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:invoice_graph">';
   ?>
	<?php
	echo '<label for="from">From:&nbsp;</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date.php';
	echo '<br clear="all"/>';
	echo '<label for="to"> To:&nbsp</label> ';
	include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
	?>
	<br clear="all">
	<br clear="all">
   <input class="submitbutton" type="submit" name="submit" value="Submit" />
</form>

