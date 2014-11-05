<?php
function escapehtml($input){
   return htmlspecialchars($input, ENT_QUOTES);
/*
   $str = htmlspecialchars($input, ENT_QUOTES);
   $pattern = "/&#039;/"; $replacement = "''";
   return preg_replace($pattern, $replacement, $str);
*/
}

function escapeescapehtml($input) {
   return htmlspecialchars_decode($input, ENT_QUOTES);
}
 
function encodeURIComponent($str) {
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($str), $revert);
}

?>
