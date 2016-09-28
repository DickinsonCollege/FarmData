<?php 

$sql = "select * from units";
$result = $dbcon->query($sql);
$conversion = array();
$default_unit = array();
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $conversion[$row['crop']][$row['unit']] = $row['conversion'];
}
$sql = "select * from product";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $conversion[$row['product']][$row['unit']] = 1;
   $default_unit[$row['product']] = $row['unit'];
}

$sql = "select * from plant";
$result = $dbcon->query($sql);
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
   $default_unit[$row['crop']] = $row['units'];
}

$sql = "select * from targets where active = 1 order by targetName";
$targs = array();
$resultt = $dbcon->query($sql);
while ($rowt = $resultt->fetch(PDO::FETCH_ASSOC)) {
   $targs[] = $rowt['targetName'];
}

?>
<script type="text/javascript">
var conversion = eval(<?php echo json_encode($conversion);?>);
var default_unit = eval(<?php echo json_encode($default_unit);?>);
var targs = eval(<?php echo json_encode($targs);?>);

</script>
