<script type="text/javascript">
function updateTime() {
  var timeBox = document.getElementById("time");
  var mins = parseInt(timeBox.value);
  if (isNaN(mins)) {
     mins = 1;
  }
  timeBox.value = mins + 1;
}

var timer = null;

function delay() {
   timer = window.setInterval(updateTime, 60000);
}

window.setTimeout(delay, 60000);

function stopTimer() {
   window.clearInterval(timer);
//   document.getElementById("time").value = 1;
}
</script>
