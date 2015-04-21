<script type="text/javascript">
function show_warning() {
<?php  if ($_SESSION['seed_order']) {
echo 'return confirm("Editing this record will NOT adjust the " +
    "inventory of the corresponding seed codes.\n\nDo you want to continue?\n");';
} else {
   echo 'return true;';
}
?>
}
function show_delete_warning() {
<?php  if ($_SESSION['seed_order']) {
   echo 'return confirm("Deleting this record will NOT adjust the " +
       "inventory of the corresponding seed codes.\n\nDo you want to continue?\n");';
   } else {
      echo 'return confirm("Confirm deletion?");';
   }
?>
}

function warn_delete() {
   return confirm("Confirm deletion?");
}
</script>
