<script type="text/javascript">
function show_warning() {
<?php  if ($_SESSION['seed_order']) {
echo 'return confirm("Editing or deleting this record will NOT adjust the " +
    "inventory of the corresponding seed codes.\n\nDo you want to continue?\n");';
} else {
   echo 'return true;';
}
?>
}
</script>
