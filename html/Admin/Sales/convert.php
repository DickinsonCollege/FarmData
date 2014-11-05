<?php 

$sql = "select * from units";
$result = mysql_query($sql);
echo mysql_error();
$conversion = array();
$default_unit = array();
while ($row = mysql_fetch_array($result)) {
   $conversion[$row['crop']][$row['unit']] = $row['conversion'];
}
$sql = "select * from product";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   $conversion[$row['product']][$row['unit']] = 1;
   $default_unit[$row['product']] = $row['unit'];
}

$sql = "select * from plant";
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result)) {
   $default_unit[$row['crop']] = $row['units'];
}

$sql = "select * from targets where active = 1 order by targetName";
$targs = array();
$resultt = mysql_query($sql);
echo mysql_error();
while ($rowt = mysql_fetch_array($resultt)) {
   $targs[] = $rowt['targetName'];
}

?>
<script type="text/javascript">
var conversion = eval(<?php echo json_encode($conversion);?>);
var default_unit = eval(<?php echo json_encode($default_unit);?>);
var targs = eval(<?php echo json_encode($targs);?>);

</script>
