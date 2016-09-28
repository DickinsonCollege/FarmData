<?php
$sql = "show columns from config";
try {
   $res = $dbcon->query($sql);
} catch (PDOException $p) {
   die($p->getMessage());
}
$ids = array();
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
   $ids[] = $row['Field'];
}
$sql = "select * from config";
try {
   $res = $dbcon->query($sql);
} catch (PDOException $p) {
   die($p->getMessage());
}
$row = $res->fetch(PDO::FETCH_ASSOC);
for ($i = 0; $i < count($ids); $i++) {
   $_SESSION[$ids[$i]] = $row[$ids[$i]];
}
?>
