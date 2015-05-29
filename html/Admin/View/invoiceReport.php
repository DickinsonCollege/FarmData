<?php session_start(); ?>
<?php
	include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
	include $_SERVER['DOCUMENT_ROOT'].'/design.php';
?>
<center><h2 class="hi"> Select Invoice Records: </h2></center>

<form class = "pure-form pure-form-aligned" method="GET" action="invoiceGraph.php">
	<?PHP
	echo '<input type="hidden" name = "tab" value = "admin:admin_view:view_graphs:invoice_graph">';
   ?>
	<?php
	echo '<div class = "pure-control-group">';
	echo '<label for="from">From:</label>';
	include $_SERVER['DOCUMENT_ROOT'].'/date.php';
	echo '</div>';

	echo '<div class = "pure-control-group">';
	echo '<label for="to">To:</label>';
	include $_SERVER['DOCUMENT_ROOT'].'/date_transdate.php';
	echo '</div>';
	?>
	<br clear="all">
   <input class="submitbutton pure-button wide" type="submit" name="submit" value="Submit" />
</form>

