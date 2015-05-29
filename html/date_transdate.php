<script type="text/javascript">
function addFieldID() {return;}
function addInput() {return;}
function overlay() {return;}
</script>
<?php 
// if ($_SESSION['mobile']) echo "<br clear='all'/>";
?>
<!--
<div class= "styled-select">
-->
<select name="tmonth" id="tmonth" class="mobile-month-select">
<?php
$tcurMonth = strftime("%m");
echo '<option value='.$tcurMonth.' selected>'.date("F", mktime(0,0,0, strftime("%m"),10)).' </option>';
for($month = $tcurMonth - $tcurMonth+1; $month < 13; $month++) {
        echo "\n<option value =\"$month\">".date("F", mktime(0, 0, 0, $month, 10))."</option>";

}
?>
</select>
<select name="tday" id="tday" class="mobile-day-select">  
<?php
$tcurDay = strftime("%e");
echo '<option value='.$tcurDay.' selected>'.$tcurDay.' </option>';
for($day = $tcurDay - $tcurDay+1; $day < 32; $day++) {
        echo "\n<option value =\"$day\">$day</option>";
}
?>
</select>
<select name="tyear" id="tyear" class="mobile-year-select" onChange="addInput();overlay();addFieldID();">
<?php
$tcurYear = strftime("%Y");
echo '<option value='.$tcurYear.' selected>'.$tcurYear.'</option>';
for($year = $tcurYear - 3; $year < $tcurYear+5; $year++) {
        echo "\n<option value =\"$year\">$year</option>";
}
?>
</select>
<!--
</div>
-->
