<?php
session_start();
?>
<!--
<!DOCTYPE html>
<?php
// $_SESSION['mobile']=1;
if (!$_SESSION['mobile']) {
   echo "<html>";
}
?>
-->
<head><meta name="google-translate-customization" content="8bca6d16755ec7ea-88689800f992b82a-g914b132bacc77839-20"></meta>
 <meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/apple-touch-icon.png" rel="apple-touch-icon" />
<link href="/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
<link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
<link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
<link href="/apple-touch-icon-180x180.png" rel="apple-touch-icon" sizes="180x180" />
<link href="/icon-hires.png" rel="icon" sizes="192x192" />
</head>
<?php
//$cookieLifetime = 3 * 24 * 60 * 60; // three days in seconds
//setcookie(session_name(),session_id(),time()+$cookieLifetime, '/');

echo '<link type="text/css" href="/color.css" rel = "stylesheet">';
// HTTPSON
if($_SERVER["HTTPS"] != "on") {
   header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
   exit();
}
// HTTPSOFF
$farm = $_SESSION['db'];
date_default_timezone_set('America/New_York');
include $_SERVER['DOCUMENT_ROOT'].'/utilities.php';

echo '<link rel="stylesheet" href="/pure-release-0.5.0/pure-min.css">';
if ($_SESSION['mobile']) {
	// Set initial-scale=0.3 and minimum-scale=0.3 when pushing to production server
//	echo "<meta name='viewport' content='width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1'>";
  echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
	//echo "<meta name='viewport' content='width=device-width, initial-scale=0.3, minimum-scale=0.3, maximum-scale=1'>";
	include $_SERVER['DOCUMENT_ROOT'].'/header.php';
	echo '<link type="text/css" href="/mobileTabs.css" rel = "stylesheet">';
//   echo '<link type="text/css" href="/tabs.css" rel="stylesheet">';
 /*
   echo '<link type="text/css" href="/mobileTable.css" rel = "stylesheet">';
   echo '<link type="text/css" href="/mobileDesign2.css" rel = "stylesheet">';
	echo '<link type="text/css" href="/tableDesignMobile.css" rel="stylesheet">';
 */
} else {
	echo '<link type="text/css" href="/tabs.css" rel = "stylesheet">';
 /*
   echo '<link type="text/css" href="/tableDesign.css" rel = "stylesheet">';
   echo '<link type="text/css" href="/design.css" rel = "stylesheet">';
 */
}
if (isset($_GET['tab'])) {
   $tabar = explode(":", $_GET['tab']);
   $tab = $tabar[0];
}
/*
if (!$_SESSION['mobile']) {
  echo '<div id="google_translate_element"></div>';
}
*/
?>
<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>
<script type="text/javascript" 
   src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>

<style type="text/css">iframe.goog-te-banner-frame{ display: none !important;}</style>
<style type="text/css">body {position: static !important; top:0px !important;}</style>
<title>FARMDATA</title>

<?php
echo '<div id="menubar" style="">';
/*
if ($_SESSION['admin']==1 && $farm != 'wahlst_spiralpath') {
   echo '<div class="tabs tabs7">';
} else if ($_SESSION['admin']==1 || $farm != 'wahlst_spiralpath') {
   echo '<div class="tabs tabs6">';
} else {
   echo '<div class="tabs tabs5">';
}
*/
/*
if ($_SESSION['admin']==1) {
   echo '<div class="tabs tabs7">';
} else {
   echo '<div class="tabs tabs6">';
}
*/
$num_top = $_SESSION['num_top'];
if (!$_SESSION['admin']) {
  $num_top--;
}
echo '<div class="tabs tabs'.$num_top.'">';
?>

<ul>
   <li id="li_harvest"><a href="/design.php?tab=harvest" id = "harvest_a" class = "inactivetab">Harvest</a></li>
   <li id="li_seeding"><a class = "inactivetab" href="/design.php?tab=seeding" id= "seeding_a">Seed</a></li>
<?php
if ($_SESSION['soil']) {
echo '<li id="li_soil"><a href="/design.php?tab=soil" class = "inactivetab" id= "soil_a">Soil</a></li>';
}
if ($_SESSION['notes']) {
   echo '<li id="li_notes"><a href="/design.php?tab=notes" class = "inactivetab" id= "notes_a">Comments</a></li>';
}
if ($_SESSION['labor']) {
   echo '<li id="li_labor"><a href="/design.php?tab=labor" class = "inactivetab" id= "labor_a">Labor</a></li>';
}
if ($_SESSION['admin']==1) {
   echo '<li id="li_admin"><a href="/design.php?tab=admin" class = "inactivetab" id ="admin_a">Admin</a></li>';
}
?>
   <li id="li_logout"><a href="/logout.php" class = "inactivetab" id= "logout_a">Logout</a></li>
</ul>


<?php
// Creates padding for hidden tabs for mobile
if (!$_SESSION['mobile']) {
	echo "<br clear='all'/>";
} else if ($tab=='harvest') {
	echo "<div style='padding-bottom: 0px;'></div>";
} else if ($tab=='seeding') {
	echo "<div style='padding-bottom: 0px;'></div>";
} else if ($tab=='soil') {
	echo "<div style='padding-bottom: 18px;'></div>";
} else if ($tab=='notes') {
	echo "<div style='padding-bottom: 36px;'></div>";
} else if ($tab=='labor') {
	echo "<div style='padding-bottom: 48px;'></div>";
} else if ($tab=='admin') {
	echo "<div style='padding-bottom: 60px;'></div>";
}

// Sets position of menubar for mobile
echo "<script type='text/javascript'>";

//switch back with viewport set to 1
if (!$_SESSION['mobile']) {
    // Nothing
} else if ($tab=='harvest') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-2px;');";
} else if ($tab=='seeding') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-14px;');";
} else if ($tab=='soil') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-26px;');";
} else if ($tab=='notes') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-38px;');";
} else if ($tab=='labor') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-50px;');";
} else if ($tab=='admin') {
    echo "document.getElementById('menubar').setAttribute('style', 'position:relative; top:-62px;');";
}
echo "</script>"; 
?>

</div>

<?php
// Include appropriate tab
if ($tab=='harvest') {
   include $_SERVER['DOCUMENT_ROOT'].'/hartab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:120px'></div>";
} else if ($tab=='seeding') {
   include $_SERVER['DOCUMENT_ROOT'].'/seedtab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:90px'></div>";
} else if ($tab=='soil') {
	include $_SERVER['DOCUMENT_ROOT'].'/soiltab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:70px'></div>";
} else if ($tab=='notes') {
	include $_SERVER['DOCUMENT_ROOT'].'/notetab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:20px'></div>";
} else if ($tab=='labor') {
	include $_SERVER['DOCUMENT_ROOT'].'/labortab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:30px'></div>";
} else if ($tab=='admin') {
	include $_SERVER['DOCUMENT_ROOT'].'/admintab.php';
	if ($_SESSION['mobile']) echo "<div style='padding-bottom:0px'></div>";
}
?>
<!-- to end menubar div
-->
</div>
<?php
if (!$_SESSION['mobile']) {
  echo '<div id="google_translate_element"></div>';
}
?>

<div id="here">
</div>

<div id="alert" style="display:block;border:2px solid;border-color:ivory;">
<center>&nbsp;</center>
</div>

<?php
if (isset($_GET['tab'])) {
   echo '<script type="text/javascript">';
   echo "\n";
   $tabs = explode(":", $_GET['tab']);
   echo 'var dv;';
   foreach ($tabs as $tab) {
      echo 'document.getElementById("'.$tab.'_a").setAttribute("class","activetab");';
      echo "\n";
      echo 'dv = document.getElementById("'.$tab.'");';
      echo "\n";
      echo 'if (dv != null) {';
      echo "\n";
      echo '   dv.style.display = "block";';
      echo "\n";
      echo "}";
      echo "\n";
   }
   echo '</script>';
   echo "\n";
}

// Takes care of tab visibility on mobile phone
if ($_SESSION['mobile']) {
	echo "<script type='text/javascript'>";
	
	$navNum = count($tabs);
	$parentTab = $tabs[$navNum-1];
	if ($navNum == 0) {
		// Nothing, show the Main Navigation Menu tabs
	} else {
		// Sets Main Nagivation Menu tabs to hidden if not on home page
		echo "var ele = document.getElementById('menubar');";
		echo "var numTabs = ele.children[0].className;";
		echo "ele.children[0].setAttribute('class', numTabs + 'nospace');";
		echo "document.getElementById('harvest_a').setAttribute('class', 'inactivetab hiddentab');";
		echo "document.getElementById('seeding_a').setAttribute('class', 'inactivetab hiddentab');";
		if ($farm != 'wahlst_spiralpath') {
			echo "document.getElementById('soil_a').setAttribute('class', 'inactivetab hiddentab');";
		}
		echo "document.getElementById('notes_a').setAttribute('class', 'inactivetab hiddentab');";
		echo "document.getElementById('labor_a').setAttribute('class', 'inactivetab hiddentab');";
		if ($_SESSION['admin'] == 1) {
			echo "document.getElementById('admin_a').setAttribute('class', 'inactivetab hiddentab');";
		}
		echo "document.getElementById('logout_a').setAttribute('class', 'inactivetab hiddentab');";

		// Sets all tabs to hidden and inactive
		foreach ($tabs as $tab) {
			echo "var ele = document.getElementById('".$tab."');";
			echo "if (ele != null) {";
				echo "ele.setAttribute('class', 'hiddentab');";
				echo "numTabs = ele.children[0].className;";
				echo "ele.children[0].setAttribute('class', numTabs + 'nospace');";
				echo "var listElements = ele.children[0].children[0].children;";
	
				echo "for (i = 0; i < listElements.length; i++) {
					if (listElements[i].tagName == 'LI') {
						listElements[i].children[0].setAttribute('class', 'inactivetab hiddentab');
						listElements[i].setAttribute('style', 'display:none;');
					}
				}";
	
			echo "}";
		}

		// Sets selected tabs as visible and active
		foreach ($tabs as $tab) {
			echo "var ele = document.getElementById('".$tab."' + '_a');";
			echo "ele.setAttribute('class', 'activetab visibletab');";
			echo "ele.parentNode.setAttribute('style', 'display:inline-block');";
		}

		// Makes the lowest level of tabs visible and inactive
		echo "var ele = document.getElementById('".$parentTab."');";
		echo "if (ele != null) {";
			echo "numTabs = ele.children[0].className;";
			echo "ele.children[0].setAttribute('class', numTabs.slice(0, -7));";
			echo "var listElements = ele.children[0].children[0].children;";
	
			echo "for (i = 0; i < listElements.length; i++) {
				if (listElements[i].tagName == 'LI') {	
					listElements[i].children[0].setAttribute('class', 'inactivetab visibletab');	
					listElements[i].setAttribute('style', 'display:inline-block;');	
				}
			}";

		echo "}";
	}
	echo "</script>";
}
?>
