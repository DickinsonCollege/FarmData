<?php
session_start();
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'].'/connection.php';
// include $_SERVER['DOCUMENT_ROOT'].'/Admin/authAdmin.php';
$user = $_SESSION['dbuser'];
if ($user == "guest" || $user == "") {
   die("Files access not authorized.");
}
?>
<link type="text/css" href="/tableDesign.css" rel = "stylesheet">
<style>
table, th, td {
  border: 1px solid black;
}
table {
  width: 1000px !important;
}
</style>

<?php

//======================================================================
//
// Name: Web File Browser
// Description: A web file browser written in PHP
// Version: 0.4 beta 15
// 
// License: This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
//======================================================================

// ---------------- Things that can be customized... -------------------

$title = "Web File Browser 0.4b15";// Title (may contain HTML tags)
$windowtitle = $title;             // Window title (text only)
$defaultstatusmsg = $title;        // Default status message (text only)

$bodybgcolor  = "#FFFFFF";         // Background color of page body
$bodybgcolor  = "Ivory";         // Background color of page body
$bodyfgcolor  = "#000000";         // Foreground color of page body
$thbgcolor    = "#D0D0D0";         // Background color of table headers
$thbgcolor    = "wheat";         // Background color of table headers
$thfgcolor    = "#000000";         // Foreground color of table headers
$tdbgcolor    = "#F0F0F0";         // Background color of table cells
$tdbgcolor    = "white";         // Background color of table cells
$tdfgcolor    = "#000000";         // Foreground color of table cells
$infocolor    = "#008000";         // Info messages foreground color
$warningcolor = "#FF8000";         // Warning messages foreground color
$errorcolor   = "#FF0000";         // Error messages foreground color
$linkcolor    = "#0000FF";         // Link color
$actlinkcolor = "#FF0000";         // Active link color

$trashcan = "wfbtrash";            // Trash can (must be located in base directory)
$trashcaninfofileext = "wfbinfo";  // Extension of information file in trash can

$filealiases = true;               // File aliasing feature
$filealiasext = "wfbalias";        // File alias extension

$defaultsortby = "name";           // Default sort mode (name/size/date)
$hidedotfiles = true;              // Hide dot-files (obsolete : use $hidefilepattern instead)
$hidefilepattern = "^(CVS|\..*)$"; // All files matching that pattern will be hidden
$showunixattrs = false;            // Show perms / owner / group (UNIX)
$filemode = 0664;                  // Create mode for files (UNIX)
$dirmode = 0775;                   // Create mode for directories (UNIX)
$uploadmaxsize = 2097152;          // Max file size for uploads (check your php.ini)

$readmefile = "wfbreadme.html";    // README file name (empty means no README file)
$showreadmefile = false;           // Allows README file to be in file list

$useimages = false;                // Use images, set to false by default to respect the philosophy
$imagesdir = "wfbimages";          // Images directory (must be located in base directory)
$showimagesdir = false;            // Show images directory
$trashcanimage = "trashcan.png";   // Image for trash can
$upperdirimage = "upperdir.png";   // Image for upper and main directory
$opendirimage = "opendir.png";     // Image for open directory
$dirimage = "dir.png";             // Image for simple directory
$fileimage = "file.png";           // Image for file directory
$editimage = "edit.png";           // Image for edit action
$viewimage = "view.png";           // Image for view action

$searchmaxlevels = 10;             // Search levels (max depth in sub directories for searches, 0 means no limits)
$downloadimage = "download.png";   // Image for download action

$editcols = 80;                    // Number of columns for edit area
$editrows = 24;                    // Number of rows for edit area
$defaultfileformat = "dos";        // Default file format when editing and saving (dos/unix)
$viewextensions = array(           // Viewable extensions (empty array means every file is viewable)
	"txt", "cgi", "sh", "sql",
	"php", "php3", "jsp", "asp",
	"htm", "html", "shtml", "xml", "wml",
	"js", "json", "css"
);

$authmethod = "none";      // Do not require user authentication
//$authmethod = "session"; // Use builtin session-based authentication (needs the PHP sessions)
//$authmethod = "realm";   // Use builtin browser's basic realm authentication
//$authmethod = "server";  // Require server based authentication (such as Apache's .htaccess)

$realmname = "Web File Browser";  // Realm name for use with $authmethod = "realm"

$noauthprofile = "full";          // Default profile used when $authmethod = "none"

$allowunknownusers = false;       // If set to false, any server authenticated but locally unknown user
                                  // gets the unknown-user profile (see bellow)
$unknownuserprofile = "readonly"; // Profile used for locally unknown users

// ---- PROFILES ----
// You can create as many profiles as you need using these samples

$profile = array(
	"full" => array(
		"allowmove" => true,                 // Allows file and directory moving
		"allowrename" => true,               // Allows file and directory renaming
		"allowalias" => true,                // Allows file aliasing
		"allowcopy" => true,                 // Allows file copying
		"allowdelete" => true,               // Allows file deletion
		"allowremovedir" => true,            // Allows directory deletion
		"allowcreatefile" => true,           // Allows file creation
		"allowcreatedir" => true,            // Allows directory creation
		"allowupload" => true,               // Allows file uploads
		"allowurlupload" => true,            // Allows file uploads from URL
		"allowbrowsetrashcan" => true,       // Allows browsing of trash can
		"allowemptytrashcan" => true,        // Allows emptying of trash can
		"allowrestorefromtrashcan" => true,  // Allows restore files from trash can
		"allowdownload" => true,             // Allows file download
		"allowedit" => true,                 // Allows file edition
		"allowshow" => true,                 // Allows file viewing (useful only if allowedit is false)
		"allowsearch" => true,               // Allows searches
		"allowregexpsearch" => true          // Allows optional use of regular expressions in searches
	),
	"readonly" => array(
		"allowmove" => false,                // Allows file and directory moving
		"allowrename" => false,              // Allows file and directory renaming
		"allowalias" => false,               // Allows file aliasing
		"allowcopy" => false,                // Allows file copying
		"allowdelete" => false,              // Allows file deletion
		"allowremovedir" => false,           // Allows directory deletion
		"allowcreatefile" => false,          // Allows file creation
		"allowcreatedir" => false,           // Allows directory creation
		"allowupload" => false,              // Allows file uploads
		"allowurlupload" => false,           // Allows file uploads from URL
		"allowbrowsetrashcan" => false,      // Allows browsing of trash can
		"allowemptytrashcan" => false,       // Allows emptying of trash can
		"allowrestorefromtrashcan" => false, // Allows restore files from trash can
		"allowdownload" => true,             // Allows file download
		"allowedit" => false,                // Allows file edition
		"allowshow" => true,                 // Allows file viewing (useful only if allowedit is false)
		"allowsearch" => true,               // Allows searches
		"allowregexpsearch" => true          // Allows optional use of regular expressions in searches
	)
);

// ---- USERS ----
// You can create as may users as you need using this templates :

$user = array(
	"admin" => array(
		"password" => "adminpwd",
		"profile" => "full"
	),
	"user" => array(
		"password" => "userpwd",
		"profile" => "readonly"
	)
);

// ---- Things that **may** be customized (but without any warranty)... ----

// *** I INSIST *** : you be careful what you do here !
// Many people ask me questions because of their mis-usage of these parameters...

$basedir = @dirname(__FILE__);    // Base directory = local directory
//$basedir = "/var/www/html/files";          // Base directory = custom directory (UNIX)
$basedir = getcwd()."/files/".$_SESSION['db'];
// }
//$basedir = "c:/My Documents";   // Base directory = custom directory (WINDOWS)

// Remember that the trash can must be located in the base directory (local or custom)

$filelinks = true;                 // Links on files (inhibited with a custom $basedir
                                   // unless you specify $basevirtualdir), works fine
                                   // when $basedir = local directory

$basevirtualdir = "";              // If you have set a custom $basedir AND $filelinks = true
                                   // and if the base directory is accessible thru a
                                   // virtual directory of the webserver
                                   // set this variable (eg. "/virtualfoo/virtualbar")
                                   // in all other cases let it empty !

// ---- Local settings -----------------------------------------------------

// Charset
$charset = "utf-8";

// Date format
$dateformat = "m-d-Y H:i:s";       // Date format. Here are some other examples (that you can combine) :
                                   // "M D, Y"   = Dec Fri, 2002
                                   // "m/d/y"    = 12/20/02
                                   // "m-d-y"    = 12-20-02
                                   // "l M d, Y" = Friday Dec 20, 2002
                                   // "F dS, Y"  = December 20th, 2002
                                   // "H:i:s"    = 24 hour time with seconds
                                   // "h:i a"    = 12 hour time with am,pm
                                   // etc...

// Messages
//  If you want another language just replace this array by the one
//  in your favorite language file (an include file is not done to keep
//  the whole code in 1 single file)
$messages = array(
	"rlm1"=>"Authentication required",
	"rlm2"=>"Authentication error",
	"rlm3"=>"Username",
	"rlm4"=>"Password",
	"rlm5"=>"Login",
	"rlm6"=>"Logout",
	"trc0"=>"Empty",
	"trc1"=>"Trash can emptied",
	"trc2"=>"Trash can was not fully emptied",
	"trc3"=>"Unable to read trash can",
	"trc9"=>"Empty trash can",
	"rst0"=>"Restore",
	"rst1"=>"Invalid name for file to restore",
	"rst2"=>"Restore only works in trash can",
	"rst3"=>"All selected files restored",
	"rst4"=>"Unable to restore file %VAR1%",
	"rst5"=>"No name for file to restore",
	"rst9"=>"Restore <b>selected</b> file",
	"mov0"=>"Move",
	"mov1"=>"Invalid name for file(s) or folder(s) to move",
	"mov2"=>"Invalid destination folder for file(s) or folder(s) to move",
	"mov3"=>"All selected file(s) or folder(s) moved to %VAR1%",
	"mov4"=>"Unable to move file or folder %VAR1% to %VAR2%",
	"mov5"=>"Destination folder %VAR1% is not a valid folder",
	"mov6"=>"No name or destination folder for file(s) or folder(s) to move",
	"mov9"=>"Move <b>selected</b> file(s) or folder(s) to <b>selected</b> folder",
	"ren0"=>"Rename",
	"ren1"=>"Invalid name for file to rename",
	"ren2"=>"Invalid new name for file to rename",
	"ren3"=>"File %VAR1% renamed to %VAR2%",
	"ren4"=>"Unable to rename file %VAR1% to %VAR2%",
	"ren5"=>"No name or new name for file rename",
	"ren9"=>"Rename <b>selected</b> file or folder to",
	"cpy0"=>"Copy",
	"cpy1"=>"Invalid name for file to copy",
	"cpy2"=>"Invalid copy name for file to copy",
	"cpy3"=>"File %VAR1% copied to %VAR2%",
	"cpy4"=>"Unable to copy file %VAR1% to %VAR2%",
	"cpy5"=>"Can't copy directories",
	"cpy6"=>"No name or copy name for file to copy",
	"cpy9"=>"Copy <b>selected</b> file to",
	"als0"=>"Alias",
	"als1"=>"Invalid name for file to alias",
	"als2"=>"File %VAR1% aliased",
	"als3"=>"Unable to alias file %VAR1%",
	"als4"=>"File %VAR1% was un-aliased",
	"als5"=>"File %VAR1% was not aliased",
	"als6"=>"Can't alias directories",
	"als7"=>"No name for file to alias",
	"als9"=>"Alias <b>selected</b> file with",
	"cre0"=>"Create file",
	"cre1"=>"Invalid name for file to create",
	"cre2"=>"File %VAR1% created",
	"cre3"=>"Unable to create file %VAR1%",
	"cre4"=>"No name for file to create",
	"cre9"=>"Create new file",
	"sav1"=>"Invalid name for file save",
	"sav2"=>"Unable to save file %VAR1%",
	"sav3"=>"No name for file to save",
	"sav4"=>"Save",
	"sav5"=>"Cancel",
	"sav6"=>"DOS / WINDOWS format",
	"sav7"=>"UNIX format",
	"del0"=>"Delete",
	"del1"=>"Invalid name for file to delete",
	"del4"=>"All selected file(s) moved to trash can",
	"del5"=>"Unable to move file %VAR1% to trash can",
	"del6"=>"No name for file to delete",
	"del7"=>"Folder %VAR1% is not a file",
	"del9"=>"Delete <b>selected</b> file(s)",
	"rmd0"=>"Remove",
	"rmd1"=>"Invalid name for folder to remove",
	"rmd2"=>"Folder %VAR1% removed",
	"rmd3"=>"Unable to remove folder %VAR1% (not empty ?)",
	"rmd4"=>"No name for folder to remove",
	"rmd5"=>"File %VAR1% is not a folder",
	"rmd9"=>"Remove <b>selected</b> folder",
	"fup0"=>"Upload",
	"fup1"=>"Invalid name for file to upload",
	"fup2"=>"Upload of file %VAR1% succeeded",
	"fup3"=>"Upload of file %VAR1% aborted",
	"fup4"=>"No name for file to upload",
	"fup9"=>"Upload file",
	"uup0"=>"URL Upload",
	"uup1"=>"Invalid URL to upload",
	"uup2"=>"URL %VAR1% uploaded to %VAR2%",
	"uup3"=>"Unable to upload %VAR1%",
	"uup4"=>"No URL to upload",
	"uup9"=>"Upload file from URL",
	"mkd0"=>"Create folder",
	"mkd1"=>"Invalid name for folder to create",
	"mkd2"=>"Folder %VAR1% created",
	"mkd3"=>"Unable to create folder %VAR1%",
	"mkd4"=>"No name for folder to create",
	"mkd9"=>"Create new folder",
	"edt1"=>"Invalid name for file to edit",
	"edt2"=>"Invalid name for file to view",
	"edt3"=>"Invalid extension for file to edit",
	"edt4"=>"Invalid extension for file to view",
	"edt5"=>"Unable to read file %VAR1%",
	"edt6"=>"No name for file to edit",
	"edt7"=>"No name for file to view",
	"edt8"=>"Edit file",
	"edt9"=>"View file",
	"edt10"=>"E", // E(dit action)
	"edt11"=>"V", // V(iew action)
	"edt12"=>"Return to file list",
	"dir1"=>"Unable to read folder",
	"dir2"=>"Main folder",
	"dir3"=>"Up one folder",
	"dir4"=>"Trash can",
	"dir5"=>"Sub-folder",
	"tab1"=>"Sel", // Sel(ection)
	"tab2"=>"To",
	"tab3"=>"Name",
	"tab4"=>"Size",
	"tab5"=>"Date",
	"tab6"=>"Perms",
	"tab7"=>"Owner",
	"tab8"=>"Group",
	"tab9"=>"Read<br/>Only",
	"tab10"=>"Action",
	"tab11"=>"directories",
	"tab12"=>"files",
	"tab13"=>"Kb", // K(ilo)b(ytes)
	"tab14"=>"Yes",
	"act1"=>"Unknown or unsuitable action",
	"act2"=>"Are you sure" ,
	"act3"=>"No file or destination folder selected",
	"act4"=>"No file selected",
	"act5"=>"No new name for file rename",
	"act6"=>"No copy name for file to copy",
	"act7"=>"Too many files or folders selected",
	"act8"=>"Select only files",
	"act9"=>"Select a folder",
	"sch1"=>"Search file(s) from the current folder",
	"sch2"=>"Search",
	"sch3"=>"No files found matching %VAR1%",
	"sch4"=>"Search results for %VAR1%",
	"sch5"=>"Searched folder",
	"sch6"=>"No search pattern",
	"sch7"=>"Use regular expression",
	"sch8"=>"Go to folder of <b>selected</b> file",
	"sch9"=>"Go to folder",
	"dwn1"=>"D", // D(ownload action)
	"dwn2"=>"Invalid name for file to download",
	"dwn3"=>"Unable to download file",
	"dwn4"=>"No name for file to download",
	"dwn5"=>"Download file",
	"inf1"=>"Sort files by name",
	"inf2"=>"Sort files by size",
	"inf3"=>"Sort files by date",
	"inf4"=>"Go to folder",
	"inf5"=>"Display file",
	"inf6"=>"Go to main folder",
	"inf7"=>"Go to up one folder",
	"inf8"=>"Go to trash can"
);

// ---------------------------------------------------------------------

// Debug to web browser console
function debug($msg) {
	echo "<script type=\"text/javascript\">console.log(\"".htmlspecialchars($msg)."\")</script>";
}

// Checks and rebuilds sub-directory
function extractSubdir($d) {
	global $basedir;
	
	$tmp = "";
	if ($d != "") {
		$rp = ereg_replace ( "((.*)\/.*)\/\.\.$", "\\2", $d );
		$tmp = strtr ( str_replace ( $basedir, "", $rp ), "\\", "/" );
		while ( $tmp [0] == '/' )
			$tmp = substr ( $tmp, 1 );
	}
	return $tmp;
}

// Returns full file path
function getFilePath($f, $sd = "") {
	global $basedir, $subdir;
	
	return $basedir . "/" . (($sd != "") ? $sd : $subdir) . "/" . @basename ( $f );
}

// Return UNIX file perms
function getFilePerms($p) {
	if (($p & 0xc000) === 0xc000) $type = 's';
	else if (($p & 0x4000) === 0x4000) $type = 'd';
	else if (($p & 0xa000) === 0xa000) $type = 'l';
	else if (($p & 0x8000) === 0x8000) $type = '-';
	else if (($p & 0x6000) === 0x6000) $type = 'b';
	else if (($p & 0x2000) === 0x2000) $type = 'c';
	else if (($p & 0x1000) === 0x1000) $type = 'p';
	else $type = '?';
	
	$u ["r"] = ($p & 00400) ? 'r' : '-';
	$u ["w"] = ($p & 00200) ? 'w' : '-';
	$u ["x"] = ($p & 00100) ? 'x' : '-';
	$g ["r"] = ($p & 00040) ? 'r' : '-';
	$g ["w"] = ($p & 00020) ? 'w' : '-';
	$g ["x"] = ($p & 00010) ? 'x' : '-';
	$o ["r"] = ($p & 00004) ? 'r' : '-';
	$o ["w"] = ($p & 00002) ? 'w' : '-';
	$o ["x"] = ($p & 00001) ? 'x' : '-';
	
	if ($p & 0x800) $u ["x"] = ($u [x] == 'x') ? 's' : 'S';
	if ($p & 0x400) $g ["x"] = ($g [x] == 'x') ? 's' : 'S';
	if ($p & 0x200) $o ["x"] = ($o [x] == 'x') ? 't' : 'T';
	
	return $type . $u ["r"] . $u ["w"] . $u ["x"] . $g ["r"] . $g ["w"] . $g ["x"] . $o ["r"] . $o ["w"] . $o ["x"];
}

// Checks file name
function checkFileName($f) {
	global $subdir, $thisfile, $hidedotfiles, $hidefilepattern, $trashcan, $trashcaninfofileext, $showimagesdir, $imagesdir, $readmefile, $showreadmefile, $filealiases, $filealiasext;

	if (!isset($f) || $f == "" || preg_match("/\.\.\//", $f)) return false;
	$f = @basename($f);
	
	return !(
		   ($subdir == "" && strtolower($f) == $thisfile)
		|| ($subdir == "" && $f == $trashcan)
		|| (!$showimagesdir && (($subdir == "" && $f == $imagesdir) || $subdir == $imagesdir))
		|| ($hidedotfiles && ($f[0] == '.'))
		|| ($hidefilepattern != "" && ereg($hidefilepattern, $f))
		|| ($filealiases && ereg("^.*\.".strtolower($filealiasext)."$", strtolower($f)))
		|| (!$showreadmefile && $f == $readmefile)
		|| ($subdir == $trashcan && ($f == $readmefile || ereg(".*\.".strtolower($trashcaninfofileext)."$", strtolower($f))))
	);
}

// Checks for edit extension
function checkExtension($f) {
	global $viewextensions;
	
	if (count ( $viewextensions ) != 0) {
		foreach ( $viewextensions as $ext )
			if (ereg ( ".*\." . strtolower ( $ext ) . "$", strtolower ( $f ) )) return true;
		return false;
	} else {
		return true;
	}
}

// Find files matching a regexp pattern
function searchFiles($sd, $searchpattern, $level = 0) {
	global $basedir, $subdir, $searchmaxlevels, $regexpsearch, $hidefilepattern;

	$count = 0;
	if (   ($searchmaxlevels == 0)
		|| ($level < $searchmaxlevels)) {
		$dir = $basedir."/".$sd;

		if (!$regexpsearch && $level == 0)
			$searchpattern = "^".str_replace("*", ".*", str_replace("?", ".", str_replace(".", "\.", $searchpattern)))."$";

		$d = @opendir($dir); 

		while (($file = @readdir($d))) { 
			if (@is_dir($dir."/".$file) && ($file != ".") && ($file != "..")) {
				$count += searchFiles($sd."/".$file, $searchpattern, $level + 1); 
			} else if (ereg(strtolower($searchpattern), strtolower($file)) && !ereg($hidefilepattern, $file)) {
				$fp = getFilePath($file, $sd);
				addFileToList($file, $fp, ($subdir != "") ? str_replace($subdir."/", "", extractSubdir($fp)) : extractSubdir($fp), 9);
				$count++;
			}
		} 
		@closedir($d); 
	}
	return $count;
}

// Adds a file to file list
function addFileToList($file, $fp, $alias, $level, $image = "", $msg = "") {
	global $files, $subdir, $trashcan, $sortby, $showunixattrs, $dateformat, $useimages, $imagesdir, $dirimage, $fileimage, $messages;
	
	if ($alias == "")
		$alias = $file;
	
	$date = @filemtime($fp);
	$size = (@is_dir($fp)) ? - 1 : @filesize($fp); // negative size for directories
	$perms = "";
	$owner = "";
	$group = "";
	if ($showunixattrs) {
		$perms = getFilePerms(@fileperms($fp));
		if (function_exists("posix_getpwuid")) {
			$uid = @posix_getpwuid(@fileowner($fp));
			$owner = $uid["name"];
		}
		if (function_exists("posix_getgrgid")) {
			$gid = @posix_getgrgid(@filegroup($fp));
			$group = $gid["name"];
		}
	}
	
	if ($sortby == "size")
		$key = $level . " " . str_pad ( $size, 20, "0", STR_PAD_LEFT ) . " " . $alias;
	else if ($sortby == "date")
		$key = $level . " " . date ( "YmdHis", $date ) . " " . $alias;
	else
		$key = $level . " " . $alias;

	$files[$key] = array(
		"name" => $file,
		"alias" => (($useimages) ? "<img src=\"$imagesdir/".(($image != "") ? $image : ((@is_dir($fp)) ? $dirimage : $fileimage))."\" style=\"text-align: center;\">&nbsp;" : "").(($subdir == $trashcan) ? ereg_replace("(.*)\.[0-9]*$", "\\1", $alias) : $alias),
		"level" => $level,
		"path" => $fp,
		"size" => $size,
		"date" => date($dateformat, $date),
		"perms" => $perms,
		"owner" => $owner,
		"group" => $group,
		"dir" => @is_dir($fp),
		"link" => @is_link($fp),
		"readonly" => !@is_writeable($fp),
		"statusmsg" => (($msg != "") ? $msg : ((@is_dir($fp)) ? $messages["inf4"] : $messages["inf5"]))
	);
}

// Generates full message
function getMsg($class, $msgcode, $msgparam1 = "", $msgparam2 = "") {
	global $messages;

	$msg = str_replace("%VAR1%", $msgparam1, str_replace("%VAR2%", $msgparam2, $messages[$msgcode]));
	return ($class != "" ? "<p class=\"$class\">" : "").htmlspecialchars($msg).($class != "" ? "</p>" : "");
}

// Manages redirections
function redirectWithMsg($class, $msgcode, $msgparam1 = "", $msgparam2 = "", $extraparams = "") {
	global $thisscript, $subdir, $sortby;

	$msg = getMsg($class, $msgcode, $msgparam1, $msgparam2);
	header("Location: $thisscript?subdir=".rawurlencode($subdir).
           "&sortby=$sortby&msg=".rawurlencode($msg).$extraparams.
           "&tab=admin:admin_view:viewfiles");
	exit;
}

// Page header
function pageHeader() {
	global $hiddeninfo, $title, $windowtitle, $thbgcolor, $thfgcolor, $tdbgcolor, $tdfgcolor, $bodybgcolor, $bodyfgcolor, $infocolor, $warningcolor, $errorcolor, $linkcolor, $actlinkcolor, $msg, $charset, $defaultstatusmsg;

/*
	echo "<!DOCTYPE html>";
	echo "\n<html>";
	echo "\n<head>";
	echo "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">";
	echo "\n<title>$windowtitle</title>";
	echo "\n<style type=\"text/css\">";
	echo "\nbody       { background-color: $bodybgcolor; color: $bodyfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\nimg        { border: none 0px; }";
	echo "\nform       { margin: 0px; padding: 0px; }";
	echo "\ntable      { border: none 0px; border-collapse: collapse; }";
	echo "\ntd         { padding: 5px; }";
	echo "\np          { color: $bodyfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\n.info      { color: $infocolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\n.warning   { color: $warningcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\n.error     { color: $errorcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\n.fix       { font-family: Courier; font-size: 10pt; }";
	echo "\nh1         { font-family: Arial, Helvetica, sans-serif; font-size: 16pt; }";
	echo "\nh2         { font-family: Arial, Helvetica, sans-serif; font-size: 12pt; }";
	echo "\nth         { background-color: $thbgcolor; color: $thfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\ntd         { background-color: $tdbgcolor; color: $tdfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; }";
	echo "\n.tdlt      { background-color: $bodybgcolor; color: $bodyfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-align: left; vertical-align: top; }";
	echo "\n.tdrt      { background-color: $bodybgcolor; color: $bodyfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-align: right; vertical-align: top; }";
	echo "\n.tdcc      { background-color: $bodybgcolor; color: $bodyfgcolor; font-family: Arial, Helvetica, sans-serif; font-size: 10pt; text-align: center; vertical-align: center; }";
	echo "\na:link     { color: $linkcolor; text-decoration: none; }";
	echo "\na:active   { color: $actlinkcolor; text-decoration: underline; }";
	echo "\na:visited  { color: $linkcolor; text-decoration: none; }";
	echo "\na:hover    { color: $actlinkcolor; text-decoration: underline; }";
	echo "\n</style>";
//*/
	echo "\n<script type=\"text/javascript\">";
	echo "\nfunction statusMsg(txt) {";
	echo "\nif (txt == '') txt = '$defaultstatusmsg';";
	echo "\nwindow.status = txt;";
	echo "\nreturn true;";
	echo "\n}";
	echo "\n</script>";
//	echo "\n</head>";

	if ($hiddeninfo != "") echo "\n<!--\nINFO :$hiddeninfo\n-->\n";
	echo "\n<body onLoad='return statusMsg(\"\")'>\n";
}

// Return quoted string for JavaScript usage
function quoteJS($str) {
	return str_replace("'", "\\&#39;", $str);
}

// Page footer
function pageFooter() {
	echo "\n</body>";
	echo "\n</center>";
	echo "\n</html>";
}

$hiddeninfo = "";

// Getting variables
if (!empty($_POST)) extract($_POST);
if (!empty($_GET)) extract($_GET);

if (function_exists("ini_set")) {
	// Try to inhibate error reporting setting
	@ini_set("display_errors", 0);

	// Try to activate upload settings, inhibate uploads if failed
	if ($allowupload && (@get_cfg_var("file_uploads") != 1)) {
		if (@ini_set("file_uploads", 1) === true) {
			@ini_set("upload_max_filesize", $uploadmaxsize);
		} else {
			$allowupload = false;
			$hiddeninfo .= "\nUpload feature inhibited";
		}
	}

	// Try to activate URL open setting, inhibate URL uploads if failed
	if ($allowurlupload && (@get_cfg_var("allow_url_fopen") != 1)) {
		if (@ini_set("allow_url_fopen", 1) === false) {
			$allowurlupload = false;
			$hiddeninfo .= "\nURL upload feature inhibited";
		}
	}
} else {
	// Inhibate uploads if upload setting not activated
	if ($allowupload && (@get_cfg_var("file_uploads") != 1)) {
		$allowupload = false;
		$hiddeninfo .= "\nUpload feature inhibited";
	}

	// Inhibate URL uploads if URL open setting not activated
	if ($allowurlupload && (@get_cfg_var("allow_url_fopen") != 1)) {
		$allowurlupload = false;
		$hiddeninfo .= "\nURL upload feature inhibited";
	}
}

// Inhibitate file links with custom base directory
if ($filelinks && (($basedir != @dirname(__FILE__)) && ($basevirtualdir == ""))) {
	$filelinks = false;
	$hiddeninfo .= "\nFile links feature inhibited";
}

// Inhibate delete action if trash can directory is not writeable
if ($allowdelete && !@is_dir($basedir."/".$trashcan)) {
	$allowdelete = false;
	$hiddeninfo .= "\nDelete action inhibited (no trash can)";
}

// Prevents from seeing this file
$thisfile = strtolower(@basename(__FILE__));

// Turns antislashes into slashes for base directory
$basedir = strtr($basedir, "\\", "/");

// This script URI
$thisscript = $_SERVER["PHP_SELF"];

// General HTTP directives
header("Expires: -1");
header("Pragma: no-cache");
header("Cache-Control: max-age=0");
header("Cache-Control: no-cache");
header("Cache-Control: no-store");
if ($act != "download") {
	header("Content-Type: text/html; charset=$charset");
}

// Built-in authentication check
if ($authmethod == "session") {
	session_start();
	if (!isset($_SESSION["WFBUSER"])) {
		if (  isset($_POST["username"])
			&& isset($_POST["password"])
			&& isset($user[$_POST["username"]])
			&& ($_POST["password"] == $user[$_POST["username"]]["password"])) {
			$_SESSION["WFBUSER"] = $_POST["username"];
			header("Location: $thisscript");
			exit;
		} else {
			pageHeader();
			if (isset($_POST["username"])) echo getMsg("error", "rlm2");
			echo "<form name=\"authForm\" method=\"post\" action=\"$thisscript\">";
			echo "<table>";
			echo "<tr><th>".$messages["rlm3"]."</th><td><input type=\"text\" name=\"username\" value=\"".$_POST["username"]."\"></td></tr>";
			echo "<tr><th>".$messages["rlm4"]."</th><td><input type=\"password\" name=\"password\"></td></tr>";
			echo "<tr><th>&nbsp;</th><td><center><input type=\"submit\" value=\"".$messages["rlm5"]."\"></center></td></tr>";
			echo "</table>";
			echo "</form>";
			echo "<script type=\"text/javascript\">document.authForm.username.select();document.authForm.username.focus();</script>";
			pageFooter();
			exit;
		}
	} else {
		if ($act == "logout") {
			unset($_SESSION["WFBUSER"]);
			header("Location: $thisscript");
			exit;
		} else {
			$username = $_SESSION["WFBUSER"];
		}
	}
} else if ($authmethod == "realm") {
	if (  !isset($_SERVER["PHP_AUTH_USER"])
		|| (!isset($user[$_SERVER["PHP_AUTH_USER"]])
		|| ($_SERVER["PHP_AUTH_PW"] != $user[$_SERVER["PHP_AUTH_USER"]]["password"]))) {
		header("WWW-Authenticate: Basic realm=\"$realmname\"");
		header("HTTP/1.0 401 Unauthorized");
		pageHeader();
		echo getMsg("error", "rlm1");
		pageFooter();
		exit;
	} else {
		$username = $_SERVER["PHP_AUTH_USER"];
	}
} else if ($authmethod == "server") {
	if (isset($_SERVER["PHP_AUTH_USER"])) {
		$username = $_SERVER["PHP_AUTH_USER"];
	} else if (isset($_ENV["REMOTE_USER"])) {
		$username = $_ENV["REMOTE_USER"];
	}
} else {
	$username = "";
}

// Check of user's profile
if ($authmethod != "none") {
	if ($username == "") {
		pageHeader();
		echo getMsg("error", "rlm1");
		pageFooter();
		exit;
	} else if (!isset($user[$username])) {
		if (!$allowunknownusers) {
			pageHeader();
			echo getMsg("error", "rlm2");
			pageFooter();
			exit;
		} else {
			$userprofile = $unknownuserprofile;
		}
	} else {
		$userprofile = $user[$username]["profile"];
	}
} else {
	$userprofile = $noauthprofile;
}

// Setting rights
$allowmove = $profile[$userprofile]["allowmove"];
$allowrename = $profile[$userprofile]["allowrename"];
$allowalias = $profile[$userprofile]["allowalias"];
$allowcopy = $profile[$userprofile]["allowcopy"];
$allowdelete = $profile[$userprofile]["allowdelete"];
$allowremovedir = $profile[$userprofile]["allowremovedir"];
$allowcreatefile = $profile[$userprofile]["allowcreatefile"];
$allowcreatedir = $profile[$userprofile]["allowcreatedir"];
$allowupload = $profile[$userprofile]["allowupload"];
$allowurlupload = $profile[$userprofile]["allowurlupload"];
$allowbrowsetrashcan = $profile[$userprofile]["allowbrowsetrashcan"];
$allowemptytrashcan = $profile[$userprofile]["allowemptytrashcan"];
$allowrestorefromtrashcan = $profile[$userprofile]["allowrestorefromtrashcan"];
$allowdownload = $profile[$userprofile]["allowdownload"];
$allowedit = $profile[$userprofile]["allowedit"];
$allowshow = $profile[$userprofile]["allowshow"];
$allowsearch = $profile[$userprofile]["allowsearch"];
$allowregexpsearch = $profile[$userprofile]["allowregexpsearch"];

// Parameters check
if (!isset($subdir) || $subdir == ".") $subdir = "";
if (($subdir != "") && (
		strstr($subdir, "..")
		|| (!$allowbrowsetrashcan && ($subdir == $trashcan))
		|| (!$showimagesdir && ($subdir == $imagesdir)) ) ) {
	$subdir = "";
	$hiddeninfo .= "\nRedirected to base directory";
}
$subdir = extractSubdir($basedir."/".$subdir);
if (!isset($sortby)) $sortby = $defaultsortby;
if (!isset($act)) $act = "";
if (!isset($file)) {
	if (!isset($selfiles) || !is_array($selfiles)) {
		$file = "";
	} else {
		$file = $selfiles[0];
	}
}

// Array for file lists
$files = array();

// Processes actions and redirects to pages
if (($act != "edit") && ($act != "show")) {
	if ($act == "") {
		@clearstatcache();

		if ($d = @opendir($basedir."/".$subdir)) {
			// builds an indexed array for files
			if ($subdir != "") {
				addFileToList("", $basedir, "[".$messages["dir2"]."]", 0, $upperdirimage, $messages["inf6"]);
			}
			if ($subdir != $trashcan) {
				addFileToList("..", getFilePath(".."), "[".$messages["dir3"]."]", 2, $upperdirimage, $messages["inf7"]);
			}
			if ($allowbrowsetrashcan && ($subdir != $trashcan) && (@is_dir($basedir."/".$trashcan))) {
				addFileToList($trashcan, $basedir."/".$trashcan, "[".$messages["dir4"]."]", 1, $trashcanimage, $messages["inf8"]);
			}
			while ($file = @readdir($d)) {
				if (checkFileName($file)) {
					$fp = getFilePath($file);
					$fp_alias = $fp.".".$filealiasext; 

					$alias = "";
					if ($filealiases && @is_readable($fp_alias)) {
						$fd = @fopen($fp_alias, "r");
						$alias = trim(@fread($fd, @filesize($fp_alias)))." <i>(".(($subdir == $trashcan) ? ereg_replace("(.*)\.[0-9]*$", "\\1", $file) : $file).")</i>";
						@fclose($fd);
					}

					addFileToList($file, $fp, $alias, 9);
				}
			}

			@closedir($d);

			// Sort the array according to indexes
			ksort($files);
		} else {
			pageHeader();
			echo getMsg("error", "dir1", $subdir);
			pageFooter();
			exit;
		}
	} else if ($allowsearch && ($act == "search")) {
		$searchpattern = trim($searchpattern);

		if ($searchpattern != "") {
			if (!isset($regexpsearch)) $regexpsearch = false;

			@clearstatcache();

			addFileToList($subdir, getFilePath("."), "[".$messages["sch5"]."]", 1, $upperdirimage);

			if (searchFiles($subdir, $searchpattern) == 0) {
				redirectWithMsg("warning", "sch3", $searchpattern, "", "&searchpattern=".rawurlencode($searchpattern).(($allowregexpsearch) ? "&regexpsearch=$regexpsearch" : ""));
			}

			ksort($files);
		} else {
			redirectWithMsg("error", "sch6");
		}
	} else if ($allowmove && ($act == "move")) {
		for ($i = 0; $i < count($selfiles); $i++) {
			$file = $selfiles[$i];

			if (isset($file) && ($file != "") && isset($dest) && ($dest != "")) {
				if (!checkFileName($file)) {
					redirectWithMsg("warning", "mov1");
				} else if (!checkFileName($dest) && !($dest == ".." && $subdir != "")) {
					redirectWithMsg("warning", "mov2");
				} else {
					$fp = getFilePath($file);
					$fpd = ($dest == "") ? $basedir : getFilePath($dest);
					$fp_alias = $fp.".".$filealiasext;
					$fpd_alias = $fpd."/".@basename($file).".".$filealiasext;

					$destinfo = ($dest == "") ? "main directory" : (($dest == "..") ? "upper directory" : $dest);

					if (@is_dir($fpd)) {
						if (@rename($fp, $fpd."/".@basename($file))) {
							if ($filealiases && @is_readable($fp_alias)) @rename($fp_alias, $fpd_alias);
						} else {
							redirectWithMsg("error", "mov4", $file, $destinfo);
						}
					} else {
						redirectWithMsg("error", "mov5", $dest);
					}
				}
			} else {
				redirectWithMsg("warning", "mov6");
			}
		}
		redirectWithMsg("info", "mov3", $destinfo);
	} else if ($allowdelete && ($act == "delete") && ($subdir != $trashcan)) {
		for ($i = 0; $i < count($selfiles); $i++) {
			$file = $selfiles[$i];

			if (isset($file) && ($file != "")) {
				if (!checkFileName($file)) {
					redirectWithMsg("warning", "del1");
				} else {
					$fp = getFilePath($file);

					if (!@is_dir($fp) || @is_link($fp)) {
						$tr = $basedir."/".$trashcan;
						$fpd = $tr."/".@basename($file).".".date("YmdHis");
						$fpd_info = $fpd.".".$trashcaninfofileext;
						$fp_alias = $fp.".".$filealiasext;
						$fpd_alias = $fpd.".".$filealiasext;

						if (@is_dir($tr) && ($fdi = @fopen($fpd_info, "w")) && @rename($fp, $fpd)) {
							@fwrite($fdi, $fp);
							@fclose($fdi);
							if ($filealiases && @is_readable($fp_alias)) @rename($fp_alias, $fpd_alias);
						} else {
							redirectWithMsg("error", "del5", $file);
						}
					} else {
						redirectWithMsg("error", "del7", $file);
					}
				}
			} else {
				redirectWithMsg("warning", "del6");
			}
		}
		redirectWithMsg("info", "del4");
	} else if ($allowremovedir && ($act == "rmdir") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "rmd1");
			} else {
				$fp = getFilePath($file);

				if (@is_dir($fp) && !@is_link($fp)) {
					if (@rmdir($fp)) {
						redirectWithMsg("info", "rmd2", $file);
					} else {
						redirectWithMsg("error", "rmd3", $file);
					}
				} else {
					redirectWithMsg("error", "rmd5", $file);
				}
			}
		} else {
			redirectWithMsg("warning", "rmd4");
		}
	} else if ($allowrename && ($act == "rename") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "") && isset($renameto) && ($renameto != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "ren1");
			} else if (!checkFileName($renameto)) {
				redirectWithMsg("warning", "ren2");
			} else {
				$fp = getFilePath($file);
				$fpto = getFilePath($renameto);
				$fp_alias = $fp.".".$filealiasext;
				$fpto_alias = $fpto.".".$filealiasext;

				if (@rename($fp, $fpto)) {
					if ($filealiases && @is_readable($fp_alias)) @rename($fp_alias, $fpto_alias);
					redirectWithMsg("info", "ren3", $file, $renameto);
				} else {
					redirectWithMsg("error", "ren4", $file, $renameto);
				}
			}
		} else {
			redirectWithMsg("warning", "ren5");
		}
	} else if ($allowcopy && ($act == "copy") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "") && isset($copyto) && ($copyto != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "cpy1");
			} else if (!checkFileName($copyto)) {
				redirectWithMsg("warning", "cpy2");
			} else {
				$fp = getFilePath($file);
				$fpto = getFilePath($copyto);

				if (!@is_dir($fp)) {
					if (@copy($fp, $fpto)) {
						redirectWithMsg("info", "cpy3", $file, $copyto);
					} else {
						redirectWithMsg("error", "cpy4", $file, $copyto);
					}
				} else {
					redirectWithMsg("error", "cpy5");
				}
			}
		} else {
			redirectWithMsg("warning", "cpy6");
		}
	} else if ($allowalias && $filealiases && ($act == "alias") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "als1");
			} else {
				$fp = getFilePath($file);
				$fp_alias = $fp.".".$filealiasext;

				if (!@is_dir($fp)) {
					if ($aliasto != "") {
						if ($fda = @fopen($fp_alias, "w")) {
							@fwrite($fda, $aliasto);
							@fclose($fda);
							redirectWithMsg("info", "als2", $file);
						} else {
							redirectWithMsg("error", "als3", $file);
						}
					} else {
						if (@is_readable($fp_alias)) {
							@unlink($fp_alias);
							redirectWithMsg("info", "als4", $file);
						} else {
							redirectWithMsg("info", "als5", $file);
						}
					}
				} else {
					redirectWithMsg("error", "als6");
				}
			}
		} else {
			redirectWithMsg("warning", "als7");
		}
	} else if ($allowcreatedir && ($act == "mkdir") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "mkd1");
			} else {
				$fp = getFilePath($file);

				if (@mkdir($fp, $dirmode)) {
					@chmod($fp, $dirmode); // mkdir sometimes fails to set permissions
					redirectWithMsg("info", "mkd2", $file);
				} else {
					redirectWithMsg("error", "mkd3", $file);
				}
			}
		} else {
			redirectWithMsg("warning", "mkd4");
		}
		redirectWithMsg($msg);
	} else if ($allowcreatefile && ($act == "create") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "cre1");
			} else {
				$fp = getFilePath($file);

				if (@touch($fp)) {
					@chmod($fp, $filemode);
					redirectWithMsg("info", "cre2", $file);
				} else {
					redirectWithMsg("error", "cre3", $file);
				}
			}
		} else {
			redirectWithMsg("warning", "cre4");
		}
	} else if ($allowupload && ($act == "upload") && ($subdir != $trashcan)) {
		if (isset($_FILES["file"]) && ($_FILES["file"]["size"] > 0)) {
			if (!checkFileName($_FILES["file"]["name"])) {
				redirectWithMsg("warning", "fup1");
			} else {
				$fp = getFilePath($_FILES["file"]["name"]);

				if (@copy($_FILES["file"]["tmp_name"], $fp)) {
					@unlink($_FILES["file"]["tmp_name"]);
					@chmod($fp, $filemode);
					redirectWithMsg("info", "fup2", $_FILES["file"]["name"]);
				} else {
					redirectWithMsg("error", "fup3", $_FILES["file"]["name"]);
				}
			}
		} else {
			redirectWithMsg("warning", "fup4");
		}
	} else if ($allowurlupload && ($act == "urlupload") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			$url = $file;
			$file = @basename(ereg_replace("^[a-zA-Z]*\:\/(.*)$", "\\1", $url));
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "uup1");
			} else {
				$fp = getFilePath($file);

				if (($fd = @fopen($url, "r")) && ($fdd = @fopen($fp, "w"))) {
					while (!@feof($fd)) {
						fwrite($fdd, @fread($fd, 1024));
					}
					@fclose($fd);
					@fclose($fdd);
					redirectWithMsg("info", "uup2", $url, $file);
				} else {
					redirectWithMsg("error", "uup3", $url);
				}
			}
		} else {
			redirectWithMsg("warning", "uup4");
		}
	} else if ($allowemptytrashcan && ($act == "empty") && ($subdir == $trashcan)) {
		$res = true;
		if ($d = @opendir($basedir."/".$subdir)) {
			while ($file = @readdir($d)) {
				$fp = getFilePath($file);

				if (($file != ".") && ($file != "..")) {
					if (@is_dir($fp) || !@unlink($fp)) {
						$res = false;
					}
				}
			}
			@closedir($d);

			if ($res) {
				redirectWithMsg("info", "trc1");
			} else {
				redirectWithMsg("warning", "trc2");
			}
		} else {
			redirectWithMsg("error", "trc3");
		}
	} else if ($allowrestorefromtrashcan && ($act == "restore")) {
		for ($i = 0; $i < count($selfiles); $i++) {
			$file = $selfiles[$i];

			if (isset($file) && ($file != "")) {
				if (!checkFileName($file)) {
					redirectWithMsg("warning", "rst1");
				} else if ($subdir != $trashcan) {
					redirectWithMsg("warning", "rst2");
				} else {
					$f = ereg_replace("(.*)\.[0-9]*$", "\\1", $file);
					$fp = getFilePath($file);
					$fp_info = $fp.".".$trashcaninfofileext;

					$fpd = "";
					if ($fdi = @fopen($fp_info, "r")) {
						$fpd = trim(@fread($fdi, @filesize($fp_info)));
						@fclose($fdi);
					}

					$fp_alias = $fp.".".$filealiasext;
					$fpd_alias = $fpd.".".$filealiasext;

					if (@rename($fp, $fpd)) {
						@unlink($fp_info);
						if ($filealiases && @is_readable($fp_alias)) @rename($fp_alias, $fpd_alias);
					} else {
						redirectWithMsg("error", "rst4", $f);
					}
				}
			} else {
				redirectWithMsg("warning", "rst5");
			}
		}
		redirectWithMsg("info", "rst3");
	} else if ($allowedit && ($act == "save") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			if (!checkFileName($file)) {
				redirectWithMsg("warning", "sav1");
			} else {
				$fp = getFilePath($file);

				if ($fd = @fopen($fp, "w")) {
					if (!isset($fileformat)) $fileformat = $defaultfileformat;

					$data = stripslashes($data); // Strips doubled backslashes
					$data = str_replace("\r\n", "\n", $data); // Remove LF => UNIX format
					if ($fileformat == "dos") $data = str_replace("\n", "\r\n", $data); // Add LF => DOS format

					@fwrite($fd, $data);
					@fclose($fd);

					redirectWithMsg("", "File $file saved (".strtoupper($fileformat)." format)", "info");
				} else {
					$msg = getMsg("error", "sav2", $file);
					$data = stripslashes($data);
					$act = "edit"; // To re-edit file (no redirection)
				}
			}
		} else {
			redirectWithMsg("warning", "sav3");
		}
	} else if ($allowdownload && ($act == "download") && ($subdir != $trashcan)) {
		if (isset($file) && ($file != "")) {
			$subdir = @dirname($file);

			if (!checkFileName($file)) {
				redirectWithMsg("warning", "dwn2");
			} else {
				$fp = getFilePath($file);

				if (@is_readable($fp)) {
					@clearstatcache();

					header("Content-Type: application/force-download");
					header("Content-Transfer-Encoding: binary");
					header("Content-Length: ".@filesize($fp));
					header("Content-Disposition: attachment; filename=\"".@basename($file)."\"");

ob_clean();
					@readfile($fp);

					exit;
				} else {
					redirectWithMsg("error", "dwn3", $file);
				}
			}
		} else {
			redirectWithMsg("warning", "dwn4");
		}
	} else {
		redirectWithMsg("error", "act1");
	}
}

// Common part of the page
pageHeader();

        include $_SERVER['DOCUMENT_ROOT'].'/design.php';
	echo "\n<style type=\"text/css\">";
echo ".genericbutton {
color: #000000;
font-size: 18pt;
-moz-border-radius: 20px;
border-radius: 20px;
background-color: wheat;
width: auto;
height: 30;
border: solid  thin black;
margin: 2;
padding: 1;
position: relative;
text-shadow: 1px 1px 1px #000;
}";
	echo "\n</style>";
echo '<script type="text/javascript">
   var dv;
   document.getElementById("admin_a").setAttribute("class","activetab");
   dv = document.getElementById("admin");
   if (dv != null) {
      dv.style.display = "block";  
   }
   document.getElementById("admin_view_a").setAttribute("class","activetab");
   dv = document.getElementById("admin_view");
   if (dv != null) {
      dv.style.display = "block";   
   }
   document.getElementById("viewfiles_a").setAttribute("class","activetab");
   dv = document.getElementById("viewfiles");
   if (dv != null) {
      dv.style.display = "block"; 
   }
</script>';

	echo "<center><h2>$title</h2>";

	if (isset($msg)) echo $msg; // Displays message after redirection if required


if ($allowsearch) echo "<form action=\"$thisscript\" method=\"get\" name=\"searchForm\">";
echo "<p><table>";
echo "<tr><td style=\"width: ".(($showunixattrs) ? 310 : 360)."px\"><b>";
if ($useimages) echo "<img src=\"$imagesdir/$opendirimage\" align=\"center\"> ";

if ($act == "search") {
	echo getMsg("", "sch4", $searchpattern)." (";
}
if ($subdir == "") {
	echo $messages["dir2"];
} else if ($subdir == $trashcan) {
	echo $messages["dir4"];
} else {
	echo $messages["dir5"]." : ".htmlspecialchars($subdir);
}
if ($act == "search") echo ")";

echo "</b><br/>".date($dateformat);
if ($authmethod == "session" || $authmethod == "realm") {
	echo " ($username";
	if ($authmethod == "session") {
		echo " <a href=\"$thisscript?tab=admin:admin_view:viewfiles&act=logout\">".$messages["rlm6"]."</a>";
	}
	echo ")";
}
echo "</td>";

if ($allowsearch && ($subdir != $trashcan) && (($act == "") || ($act == "search"))) {
	echo "<td style=\"width: 20px;\" class=\"tdlt\">&nbsp;</td>";
	echo "<td class=\"tdlt\">";
	echo "<input name=\"act\" type=\"hidden\" value=\"search\"/>";
	echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
	echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
	echo "<input name=\"searchpattern\" type=\"text\" size=\"15\" value=\"$searchpattern\"/> ";
	echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["sch2"]."\" onClick=\"submitActForm(document.searchForm, 'searchpattern', '".quoteJS($messages["sch6"])."')\"/>";
	if ($allowregexpsearch) {
		echo "<br/><input type=\"checkbox\" value=\"true\" name=\"regexpsearch\"".(($regexpsearch) ? " checked=\"checked\"" : "")."/> ".$messages["sch7"];
	}
	echo "</td>";
}

echo"</tr>";
echo "</table>";
if ($allowsearch) echo "</form>";

// Edit or show page
if (($allowedit && ($act == "edit")) || ($allowshow && ($act == "show")) && ($subdir != $trashcan)) {
	if (isset($file) && ($file != "")) {
		if (!checkFileName($file)) {
			echo getMsg("warning", ($act == "edit") ? "edt1" : "edt2");
		} else if (!checkExtension($file)) {
			echo getMsg("warning", ($act == "edit") ? "edt3" : "edt4");
		} else {
			if (!isset($data)) {
				$fp = getFilePath($file);

				if ($fd = @fopen($fp, "r")) {
					$data = @fread($fd, @filesize($fp));
					@fclose($fd);
				} else {
					echo getMsg("error", "edt5");
				}
			}

			if ($act == "edit") {
				echo "<p><b>".$messages["edt8"]." : </b>".htmlspecialchars($file);
				
				echo "\n<script type=\"text/javascript\">";
				echo "\nfunction cancelEdit() {";
				echo	"\nf = document.editForm;";
				echo	"\nf.act.value = '';";
				echo	"\nf.file.value = '';";
				echo	"\nf.data.value = '';";
				echo	"\nf.method = 'get';";
				echo	"\nf.submit();";
				echo "\n}";
				echo "\n</script>\n";

				echo "<form action=\"$thisscript\" method=\"post\" name=\"editForm\">";
				echo "<p><table>";
				echo "<input name=\"act\" type=\"hidden\" value=\"save\"/>";
				echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
				echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
				echo "<input name=\"file\" type=\"hidden\" value=\"$file\"/>";
				echo "<tr>";
				echo "<td colspan=\"3\">";
				echo "<textarea name=\"data\" cols=\"$editcols\" rows=\"$editrows\">";
				echo htmlspecialchars($data);
				echo "</textarea>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td style=\"text-align: left;\">";
				echo "<input type=\"radio\" name=\"fileformat\" value=\"dos\"".(($defaultfileformat == "dos") ? " checked=\"checked\"" : "")."/>".$messages["sav6"];
				echo "<br/><input type=\"radio\" name=\"fileformat\" value=\"unix\"".(($defaultfileformat == "unix") ? " checked=\"checked\"" : "")."/>".$messages["sav7"];
				echo "</td>";
				echo "<td style=\"text-align: center;\">";
				echo "<input type=\"submit\" value=\"".$messages["sav4"]."\"/>";
				echo "</td>";
				echo "<td style=\"text-align: right;\">";
				echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["sav5"]."\" onClick=\"cancelEdit();\">";
				echo "</td>";
				echo "</tr>";
				echo "</table>";
				echo "</form>";
			} else {
				echo "<p><b>".$messages["edt9"]." : </b>".htmlspecialchars($file);
				echo "<p><table>";
				echo "<tr><td style=\"width: 700px;\"><pre>".htmlspecialchars($data)."</pre>&nbsp;</td></tr>";
				echo "</table>";
				echo "<p><a href=\"$thisscript?tab=admin:admin_view:viewfiles&subdir=".rawurlencode($subdir)."&sortby=$sortby\" onMouseOver='return statusMsg(\"".quoteJS($messages["edt12"])."\");' onMouseOut=\"return statusMsg('');\">".$messages["edt12"]."</a>";
			}
		}
	} else {
		echo getMsg("warning", ($act == "edit") ? "edt6" : "edt7");
	}
// File list page
} else {
	echo "\n<script type=\"text/javascript\">";
	echo "\nfunction submitListForm(action) {";
	echo	 "\nf = document.listForm;";
	echo	 "\nfilechecked = 0;";

	if ($act == "search") {
		echo "\nsubdir = '';";
		echo "\nfilesubdir = new Array();";
		reset($files);
		$i = 0;
		while (list($key, $file) = each($files)) {
			if (!@is_dir($file["path"])) echo "\nfilesubdir[".$i++."] = \"".extractSubdir(@dirname($file["path"]))."\";";
		}
	} else {
		echo "\ndirchecked = false;";
		echo "\nisdir = new Array();";
		reset($files);
		$i = 0;
		while (list($key, $file) = each($files)) {
			if ($file["level"] == 9) echo "\nisdir[".$i++."] = ".(($file["dir"]) ? "true" : "false");
		}
	}

	echo "\nif (f.elements['selfiles[]']) {";
	echo	 "\nif (f.elements['selfiles[]'].length > 1) {";
	echo		 "\nfor (i = 0; i < f.elements['selfiles[]'].length; i++) {";
	echo			 "\nif (f.elements['selfiles[]'][i].checked) {";
	echo				 "\nfilechecked++;";

	if ($act == "search") {
		echo "\nsubdir = filesubdir[i];";
	} else {
		echo "\nif (isdir[i]) dirchecked = true;";
	}

	echo			 "\n}";
	echo		 "\n}";
	echo	 "\n} else {";
	echo		 "\nif (f.elements['selfiles[]'].checked) filechecked = 1;";

	if ($act == "search") {
		echo "\nsubdir = filesubdir[0];";
	} else {
		echo "\nif (isdir[0]) dirchecked = true;";
	}

	echo	"\n}";
	echo "\n}";

	if ($act != "search") {
		echo "\ndestchecked = false;";
		echo "\nif (f.dest) {";
		echo	 "\nif (f.dest.length > 1) {";
		echo		 "\nfor (i = 0; i < f.dest.length; i++) {";
		echo			 "\nif (f.dest[i].checked) {";
		echo				 "\ndestchecked = true;";
		echo				 "\nbreak;";
		echo			 "\n}";
		echo		 "\n}";
		echo	 "\n} else {";
		echo		 "\ndestchecked = f.dest.checked;";
		echo	 "\n}";
		echo "\n}";
		echo "\nif ((action == 'empty') && confirm(\"".$messages["act2"]." ?\")) {";
		echo	 "\nf.act.value = action;";
		echo	 "\nf.submit();";
		echo "\n} else if ((action == 'move') && ((filechecked == 0) || !destchecked)) {";
		echo	 "\nalert(\"".quoteJS($messages["act3"])."\");";
		echo "\n} else if (filechecked == 0) {";
		echo	 "\nalert(\"".quoteJS($messages["act4"])."\");";
		echo "\n} else if ((action != 'delete') && (action != 'move') && (action != 'restore') && (filechecked > 1)) {";
		echo	 "\nalert(\"".quoteJS($messages["act7"])."\");";
		echo "\n} else if ((action != 'move') && (action != 'rename') && (action != 'rmdir') && dirchecked) {";
		echo	 "\nalert(\"".quoteJS($messages["act8"])."\");";
		echo "\n} else if ((action == 'rmdir') && !dirchecked) {";
		echo	 "\nalert(\"".quoteJS($messages["act9"])."\");";
		echo "\n} else if ((action == 'rename') && (f.renameto.value == '')) {";
		echo	 "\nalert(\"".quoteJS($messages["act5"])."\");";
		echo "\n} else if ((action == 'copy') && (f.copyto.value == '')) {";
		echo	 "\nalert(\"".quoteJS($messages["act6"])."\");";
		echo "\n} else if (((action == 'delete') || (action == 'rmdir')) && confirm(\"".quoteJS($messages["act2"])." ?\")) {";
		echo	 "\nf.act.value = action;";
		echo	 "\nf.submit();";
		echo "\n} else if ((action != 'delete') && (action != 'rmdir')) {";
		echo	 "\nf.act.value = action;";
		echo	 "\nf.submit();";
		echo "\n}";
	} else {
		echo "\nif (filechecked == 0) {";
		echo	 "\nalert(\"".quoteJS($messages["act4"])."\");";
		echo "\n} else if (filechecked > 1) {";
		echo	 "\nalert(\"".quoteJS($messages["act7"])."\");";
		echo "\n} else {";
		echo	 "\nf.subdir.value = subdir;";
		echo	 "\nf.act.value = '';";
		echo	 "\nf.submit();";
		echo "\n}";
	}
	echo "\n}";

	echo "\nfunction submitActForm(f, n, m) {";
	echo	 "\nif (f.elements[n].value == f.elements[n].defaultValue) {";
	echo		 "\nalert(m);";
	echo	 "\n} else {";
	echo		 "\nf.submit();";
	echo	 "\n}";
	echo "\n}";

	if (($act != "search") && ($allowmove || $allowdelete)) {
		echo "\nfunction selectAll() {";
		echo	 "\nf = document.listForm;";
		echo	 "\nc = f.selectall.checked;";
		echo	 "\nif (f.elements['selfiles[]']) {";
		echo		 "\nif (f.elements['selfiles[]'].length > 1) {";
		echo			 "\nfor (i = 0; i < f.elements['selfiles[]'].length; i++) f.elements['selfiles[]'][i].checked = c;";
		echo		 "\n} else {";
		echo			 "\nf.elements['selfiles[]'].checked = c;";
		echo		 "\n}";
		echo	 "\n}";
		echo "\n}";
	}

	echo "\n</script>\n";

	if (!empty($files)) {
		echo "<p><table>";
		echo "<form action=\"$thisscript\" method=\"post\" name=\"listForm\">";
		echo "<input name=\"act\" type=\"hidden\" value=\"\"/>";
		echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
		echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
		echo "<tr>";
		echo "<td style=\"width: 25px; height: 0\" class=\"tdcc\"></td>";
		echo "<td style=\"width: 25px; height: 0\" class=\"tdcc\"></td>";
		echo "<td style=\"width: ".(($showunixattrs) ? 250 : 300)."px; height: 0;\" class=\"tdcc\"></td>";
		echo "<td style=\"width: 100px; height: 0;\" class=\"tdcc\"></td>";
		echo "<td style=\"width: 130px; height: 0;\" class=\"tdcc\"></td>";

		if ($showunixattrs) {
			echo "<td style=\"width: 100px; height: 0;\" class=\"tdcc\"></td>";
			echo "<td style=\"width: 75px; height: 0;\" class=\"tdcc\"></td>";
			echo "<td style=\"width: 75px; height: 0;\" class=\"tdcc\"></td>";

			$nbcols = 9;
		} else {
			echo "<td style=\"width: 50px; height: 0;\" class=tdcc></td>";

			$nbcols = 7;
		}

		echo "<td style=\"width: 50 height: 0;\" class=tdcc></td>";
		echo "</tr>";

		if (($readmefile != "") && @is_readable(getFilePath($readmefile)) && ($act != "search")) {
			echo "<tr><td colspan=\"$nbcols\">";
			include(getFilePath($readmefile));
			echo "</td></tr>";
		}

		echo "<tr>";
		echo "<th>".$messages["tab1"]."</th>";
		echo "<th>".$messages["tab2"]."</th>";
		echo "<th>";
		echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&subdir=".rawurlencode($subdir)."&sortby=name".(($act == "search") ? "&act=search&searchpattern=".rawurlencode($searchpattern) : "")."\" onMouseOver=\"return statusMsg('".quoteJS($messages["inf1"])."');\" onMouseOut=\"return statusMsg('');\">".$messages["tab3"]."</a>";
		echo "</th>";
		echo "<th>";
		echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&subdir=".rawurlencode($subdir)."&sortby=size".(($act == "search") ? "&act=search&searchpattern=".rawurlencode($searchpattern) : "")."\" onMouseOver=\"return statusMsg('".quoteJS($messages["inf2"])."');\" onMouseOut=\"return statusMsg('');\">".$messages["tab4"]."</a>";
		echo "</th>";
		echo "<th>";
		echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&subdir=".rawurlencode($subdir)."&sortby=date".(($act == "search") ? "&act=search&searchpattern=".rawurlencode($searchpattern) : "")."\" onMouseOver=\"return statusMsg('".quoteJS($messages["inf3"])."');\" onMouseOut=\"return statusMsg('');\">".$messages["tab5"]."</a>";
		echo "</th>";

		if ($showunixattrs) {
			echo "<th>".$messages["tab6"]."</th>";
			echo "<th>".$messages["tab7"]."</th>";
			echo "<th>".$messages["tab8"]."</th>";
		} else {
			echo "<th>".$messages["tab9"]."</th>";
		}
		echo "<th>".$messages["tab10"]."</th>";
		echo "</tr>";

		// Files and directories
		$total = 0;
		$nbfiles = 0;
		$nbdirs = 0;
		reset($files);
		while (list($key, $file) = each($files)) {
			// Directory section
			if ($file["dir"]) {
				if (($subdir != "") || ($file["name"] != "..")) {
					echo "<tr>";
					if (($file["level"] == 9) && ($allowmove || $allowrename || $allowdelete)) {
						echo "<td><input type=\"checkbox\" name=\"selfiles[]\" value=\"".$file["name"]."\"/></td>";
					} else {
						echo "<td>&nbsp;</td>";
					}
					if (($file["level"] != 1) && $allowmove && ($subdir != $trashcan)) {
						echo "<td><input type=\"radio\" name=\"dest\" value=\"".$file["name"]."\"/></td>";
					} else {
						echo "<td>&nbsp;</td>";
					}
					echo "<td>";
					if ($file["link"]) {
						echo "<i><b>".htmlspecialchars($file["name"])."</b></i>";
					} else {
						echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&subdir=".rawurlencode(extractSubdir($file["path"]))."&sortby=$sortby\" onMouseOver=\"return statusMsg('".quoteJS($file["statusmsg"])."');\" onMouseOut=\"return statusMsg('');\">";
						echo "<b>".$file["alias"]."</b>";
						echo "</a>";
					}
					echo "</td>";
					echo "<td>&nbsp;</td>";
					echo "<td style=\"text-align: right;\">".$file["date"]."</td>";
					if ($showunixattrs) {
						echo "<td style=\"text-align: center;\"><span class=\"fix\">".$file["perms"]."</span></td>";
						echo "<td style=\"text-align: right;\">".$file["owner"]."</td>";
						echo "<td style=\"text-align: right;\">".$file["group"]."</td>";
					} else {
						echo "<td style=\"text-align: center;\">".(($file["readonly"]) ? $messages["tab14"] : "&nbsp;")."</td>";
					}
					echo "<td>&nbsp;</td>";
					echo "</tr>";

					if ($file["level"] == 9) $nbdirs++;
				}
			// File section
			} else {
				echo "<tr>";
				if ($allowmove || $allowrename || $allowcopy || $allowdelete || ($subdir == $trashcan) || ($act == "search")) {
					echo "<td><input type=\"checkbox\" name=\"selfiles[]\" value=\"".$file["name"]."\"/>&nbsp;</td>";
				} else {
					echo "<td>&nbsp;</td>";
				}
				echo "<td>&nbsp;</td>";
				echo "<td>".(($file["link"]) ? "<i>" : "");
				if ($filelinks) {
					if ($basevirtualdir == "") {
						echo "<a href=\"".str_replace("%2F", "/", rawurlencode(extractSubdir($file["path"])));
					} else {
						echo "<a href=\"".$basevirtualdir."/".rawurlencode($file["name"]);
					}
					echo "\" onMouseOver=\"return statusMsg('".quoteJS($file["statusmsg"])."');\" onMouseOut=\"return statusMsg('');\">";
					echo $file["alias"];
					echo "</a>";
				} else {
					echo htmlspecialchars($file["name"]);
				}
				echo (($file["link"]) ? "</i>" : "")."</td>";
				echo "<td style=\"text-align: right;\">".$file["size"]."</td>";
				echo "<td style=\"text-align: right;\">".$file["date"]."</td>";
				if ($showunixattrs) {
					echo "<td style=\"text-align: center;\"><span class=\"fix\">".$file["perms"]."</span></td>";
					echo "<td style=\"text-align: right;\">".$file["owner"]."</td>";
					echo "<td style=\"text-align: right;\">".$file["group"]."</td>";
				} else {
					echo "<td style=\"text-align: center;\">".(($file["readonly"]) ? $messages["tab14"] : "&nbsp;")."</td>";
				}
				echo "<td style=\"text-align: center;\">&nbsp;";
				if (($act != "search") && ($allowedit || $allowshow) && checkExtension($file["name"]) && ($subdir != $trashcan)) {
					if (!$file["readonly"] && $allowedit) {
						echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&act=edit&subdir=".rawurlencode($subdir)."&sortby=$sortby&file=".rawurlencode($file["name"])."\" onMouseOver='return statusMsg(\"".quoteJS($messages["edt8"])."\");' onMouseOut='return statusMsg(\"\");'>".(($useimages) ? "<img src=\"$imagesdir/$editimage\">" : $messages["edt10"])."</a> ";
					}
					echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&act=show&subdir=".rawurlencode($subdir)."&sortby=$sortby&file=".rawurlencode($file["name"])."\" onMouseOver='return statusMsg(\"".quoteJS($messages["edt9"])."\");' onMouseOut='return statusMsg(\"\");'>".(($useimages) ? "<img src=\"$imagesdir/$viewimage\">" : $messages["edt11"])."</a> ";
				} 
				if (($allowdownload) && ($subdir != $trashcan)) {
					echo "<a href=\"$thisscript?tab=admin:admin_view:viewfiles&act=download&subdir=".rawurlencode($subdir)."&sortby=$sortby&file=".str_replace("%2F", "/", rawurlencode(extractSubdir($file["path"])))."\" onMouseOver=\"return statusMsg('".quoteJS($messages["dwn5"])."');\" onMouseOut=\"return statusMsg('');\">".(($useimages) ? "<img src=\"$imagesdir/$downloadimage\">" : $messages["dwn1"])."</a> ";
				}
				echo "</td>";
				echo "</tr>";
				
				$total += $file["size"];
				$nbfiles++;
			}
		}
		if (($act != "search") && ($nbfiles > 0) && ($allowmove || $allowdelete)) {
			echo "<th style=\"text-align: left;\"><input type=\"checkbox\" name=\"selectall\" onClick=\"selectAll();\"></th>";
			$n = $nbcols - 1;
		} else {
			$n = $nbcols;
		}
		echo "<th colspan=\"$n\">$nbdirs ".$messages["tab11"].", $nbfiles ".$messages["tab12"]." (".round($total/1024)." ".$messages["tab13"].")</th>";
		echo "</tr>";
		echo "<tr><td class=\"tdrt\" colspan=\"$nbcols\">&nbsp;</td></tr>";

		// Action forms
		if ($allowsearch && ($act == "search")) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["sch8"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["sch9"]."\" onClick=\"submitListForm('goto');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowmove 
			&& ($subdir != $trashcan)
			&& (  (($subdir != "") && (($nbfiles > 0) || ($nbdirs > 0)))
				|| (($subdir == "") && ($nbfiles > 0) && ($nbdirs > 0)))) {
			echo "<tr>";
			echo "<td class=tdrt colspan=3>";
			echo $messages["mov9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["mov0"]."\" onClick=\"submitListForm('move');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowdelete
			&& ($subdir != $trashcan) 
			&& ($nbfiles > 0)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["del9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["del0"]."\" onClick=\"submitListForm('delete');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowremovedir
			&& ($subdir != $trashcan) 
			&& ($nbdirs > 0) 
			&& @is_dir($basedir."/".$trashcan)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["rmd9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["rmd0"]."\" onClick=\"submitListForm('rmdir');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowrename
			&& ($subdir != $trashcan)
			&& (($nbfiles > 0) || ($nbdirs > 0))) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["ren9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"text\" name=renameto size=15> ";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["ren0"]."\" onClick=\"submitListForm('rename');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowcopy
			&& ($subdir != $trashcan)
			&& ($nbfiles > 0)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["cpy9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"text\" name=\"copyto\" size=\"15\"/> ";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["cpy0"]."\" onClick=\"submitListForm('copy');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowalias
			&& ($subdir != $trashcan)
			&& ($nbfiles > 0)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["als9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"text\" name=\"aliasto\" size=\"15\"> ";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["als0"]."\" onClick=\"submitListForm('alias');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowrestorefromtrashcan
			&& ($subdir == $trashcan)
			&& ($nbfiles > 0)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["rst9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["rst0"]."\" onClick=\"submitListForm('restore');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		if (($act != "search")
			&& $allowemptytrashcan
			&& ($subdir == $trashcan)
			&& ($nbfiles > 0)) {
			echo "<tr>";
			echo "<td class=\"tdrt\" colspan=\"3\">";
			echo $messages["trc9"]." :&nbsp;";
			echo "</td>";
			echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
			echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["trc0"]."\" onClick=\"submitListForm('empty');\"/>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</form>";
		if ($subdir != $trashcan) {
			echo "<tr><td class=tdrt colspan=$nbcols>&nbsp;</td></tr>";

			if (($act != "search")
				&& $allowcreatedir) {
				echo "<tr>";
				echo "<td class=\"tdrt\" colspan=\"3\">";
				echo $messages["mkd9"]." :&nbsp;";
				echo "</td>";
				echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
				echo "<form action=\"$thisscript\" method=\"post\" name=\"createDirForm\">";
				echo "<input name=\"act\" type=\"hidden\" value=\"mkdir\"/>";
				echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
				echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
				echo "<input name=\"file\" type=\"text\" size=\"15\"> ";
				echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["mkd0"]."\" onClick=\"submitActForm(document.createDirForm, 'file', '".quoteJS($messages["mkd4"])."');\"/>";
				echo "</form>";
				echo "</td>";
				echo "</tr>";
			}
			if (($act != "search")
				&& $allowcreatefile) {
				echo "<tr>";
				echo "<td class=\"tdrt\" colspan=\"3\">";
				echo $messages["cre9"]." :&nbsp;";
				echo "</td>";
				echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
				echo "<form action=\"$thisscript\" method=\"post\" name=\"createFileForm\">";
				echo "<input name=\"act\" type=\"hidden\" value=\"create\"/>";
				echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
				echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
				echo "<input name=\"file\" type=\"text\" size=\"15\"/> ";
				echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["cre0"]."\" onClick=\"submitActForm(document.createFileForm, 'file', '".quoteJS($messages["cre4"])."');\"/>";
				echo "</form>";
				echo "</td>";
				echo "</tr>";
			}
			if (($act != "search")
				&& $allowupload) {
				echo "<tr>";
				echo "<td class=\"tdrt\" colspan=\"3\">";
				echo $messages["fup9"]." :&nbsp;";
				echo "</td>";
				echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
				echo "<form action=\"$thisscript\" method=\"post\" enctype=\"multipart/form-data\" name=\"uploadFileForm\">";
				echo "<input name=\"act\" type=\"hidden\" value=\"upload\"/>";
				echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
				echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
				echo "<input name=\"max_file_size\" type=\"hidden\" value=$uploadmaxsize/>";
				echo "<input name=\"file\" style={background:white} type=\"file\" size=\"15\"/> ";
				echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["fup0"]."\" onClick=\"submitActForm(document.uploadFileForm, 'file', '".quoteJS($messages["fup4"])."');\"/>";
				echo "</form>";
				echo "</td>";
				echo "</tr>";
			}
			if (($act != "search")
				&& $allowurlupload) {
				echo "<tr>";
				echo "<td class=\"tdrt\" colspan=\"3\">";
				echo $messages["uup9"]." :&nbsp;";
				echo "</td>";
				echo "<td class=\"tdlt\" colspan=\"".($nbcols - 3)."\">";
				echo "<form action=\"$thisscript\" method=\"post\" name=\"uploadUrlForm\">";
				echo "<input name=\"act\" type=\"hidden\" value=\"urlupload\"/>";
				echo "<input name=\"subdir\" type=\"hidden\" value=\"$subdir\"/>";
				echo "<input name=\"sortby\" type=\"hidden\" value=\"$sortby\"/>";
				echo "<input name=\"file\" type=\"text\" size=\"15\" value=\"http://\"/> ";
				echo "<input type=\"button\" class = \"genericbutton\" value=\"".$messages["uup0"]."\" onClick=\"submitActForm(document.uploadUrlForm, 'file', '".quoteJS($messages["uup4"])."');\"/>";
				echo "</form>";
				echo "</td>";
				echo "</tr>";
			}
		}

		echo "</table>";
	}
}

pageFooter();
?>
