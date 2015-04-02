<div class='header'> 
<?php
if ($_SESSION['db'] == 'dfarm') {
  $ex="";
} else {
  $ex = "ext";
}
$homeURL = "https://".$_SERVER['HTTP_HOST']."/".$ex."home.php";
?>

<!-- Back Button -->
<div class='header_container header_back'>
<?php

if (isset ($_GET['tab'])) {
	$tabSet = explode(":", $_GET['tab']);
	$numTabs = count($tabSet);
	if ($numTabs == 1) {
		$backURL = $homeURL;
	} else {
		$backURL = "https://".$_SERVER['HTTP_HOST']."/design.php?tab=";
		for ($i = 0; $i < $numTabs - 1; $i++) {
			$backURL .= $tabSet[$i];
			if ($i < $numTabs - 2) {
				$backURL .= ":";
			}
		}
	}
} else {
	$backURL = $homeURL;
}

echo "<a class='header_link' href='".$backURL."'>";

?>
<br>
<img src='/BackArrowMobile.png' width='30' height='30' style='margin-top:-15px'>
<div>Back</div>
</a>
</div>

<!-- Home Button -->
<div class='header_container header_home'>
<?php
echo "<a class='header_link' href='".$homeURL."'>";
?>
<br>
<img src='/HomeMobile.png' width='30' height='30' style='margin-top:-15px'>
<div>Home</div>
</a>
</div>

<!-- Space Between Buttons -->
<div id = "google_translate_element" class='header_container header_middle'>
</div>

<!-- Logout Button -->
<div class='header_container header_logout'>
<?php
echo "<a class='header_link' href='https://".$_SERVER['HTTP_HOST']."/logout.php'>";
?>
<br>

<img src='/LogoutMobile.png' width='30' height='30' style='margin-top:-15px'>
<div>Logout</div>
</a>
</div>

</div>
