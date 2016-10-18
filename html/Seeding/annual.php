<script type="text/javascript">

function addLastHarvestDate() {
   var annual = document.getElementById("annual").value;
   var lastharvdiv = document.getElementById("lastharvdiv");
   if (annual == 1) {
      lastharvdiv.innerHTML = "";
   } else {
      var sel = "<label>Date of Last Harvest:</label>";

      sel += '<select name="lastMonth" id="lastMonth" class="mobile-month-select">';
      var lastMonth = parseInt('<?php echo $lastMonth?>');
      if (!isNaN(lastMonth)) {
         var months = new Array(12);
         months[0] = "January";
         months[1] = "February";
         months[2] = "March";
         months[3] = "April";
         months[4] = "May";
         months[5] = "June";
         months[6] = "July";
         months[7] = "August";
         months[8] = "September";
         months[9] = "October";
         months[10] = "November";
         months[11] = "December";
         sel += "<option value=" + lastMonth + ">" + months[lastMonth - 1] + "</option>";
      } else {
         sel += '<option value="12" selected>December</option>';
      }
      sel += '<?php
         for($month = 1; $month < 13; $month++) {
            echo "<option value =\"$month\">".date("F", mktime(0, 0, 0, $month, 10))."</option>";
         }
              ?>';
      sel += '</select>';

      sel += '<select name="lastDay" id="lastDay" class="mobile-day-select">';
      var lastDay = parseInt('<?php echo $lastDay?>');
      if (!isNaN(lastDay)) {
         sel += "<option value=" + lastDay + ">" + lastDay + "</option>";
      } else {
         sel += '<option value="31" selected>31</option>';
      }
      sel += '<?php
         for($day = 1; $day < 32; $day++) {
            echo "<option value =\"$day\">$day</option>";
         }
              ?>';
      sel += '</select>';

      sel += "<select class='mobile-year-select' id='lastYear' name='lastYear'>";
      var lastYear = parseInt('<?php echo $lastYear?>');
      if (!isNaN(lastYear)) {
         sel += "<option value=" + lastYear + ">" + lastYear + "</option>";
      }
      var curYear = parseInt(document.getElementById("year").value);
      for (var yr = curYear + 1; yr < curYear + 11; yr++) {
         sel += "<option value=" + yr + ">" + yr + "</option>";
      }
      sel += "</select>";
      lastharvdiv.innerHTML = sel;
   }
}
</script>
