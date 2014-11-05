<script type="text/javascript">
function stopSubmitOnEnter(e) {

   evt= e || window.event;
   var keycode = evt.keyCode || evt.which || evt.charCode;
  if (keycode == 13) {
    e.preventDefault();
  }
  return false;
}
</script>

