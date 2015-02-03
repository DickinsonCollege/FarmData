<?php
session_start();
$_SESSION['dbuser'] = "guest";
$_SESSION['user'] = 0;
$_SESSION['username'] = 'guest';
$_SESSION['dbpass'] = "guest";
$_SESSION['db'] = "dfarm";
$_SESSION['admin'] = 1;
include 'connection.php';
include 'authentication.php';
include 'setconfig.php';
function isMobileG($agent) {

   if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|Windows (CE|phone)|xda|xiino/i',$agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800
      |pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|
      r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0
      |sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|
      to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|
      83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',$agent)){
    return 1;
} else {
    return 0; 
}
}

$useragent=$_SERVER['HTTP_USER_AGENT'];
$_SESSION['mobile'] = isMobileG($useragent);
include 'design.php';
include 'footer.php';
?>
<h1> Welcome to the College Farm!</h1> <br>
<!--
Please Click on One of the Tabs to Meet Your Needs! 
-->
<h1>Guest users: </h1>
<p>

Please join us for a free Webinar on FARMDATA on Thursday, February 19, 2015 at 1:00 p.m. Eastern
 time.  The Webinar is hosted by Small Farm Central and will include information on a possible
 pilot project with SFC.  For more information and to sign up:<br>
 <a href="http://pages.smallfarmcentral.com/farmdata/">http://pages.smallfarmcentral.com/farmdata/</a>
<p>
 The FARMDATA users manual is available at:<br>
<a href="http://sourceforge.net/p/farmdata/wiki/Manual/">http://sourceforge.net/p/farmdata/wiki/Manual/</a>
<p>
As a guest user, you will be able to access all FARMDATA features (including administrative functionality),
but you will not be able to enter or edit any data.  Attempts to enter data or modify FARMDATA 
configuration will result in error messages.
<p>
 FARMDATA is an open source project.  If you would like to download and install FARMDATA on your
own web server, see:<br>
<a href="http://sourceforge.net/projects/farmdata/">http://sourceforge.net/projects/farmdata/</a>
<br>
Installation and configuration instructions are available at:<br>
<a href="http://sourceforge.net/p/farmdata/wiki/Home/">http://sourceforge.net/p/farmdata/wiki/Home/</a>
<!--
click <a href="FARMDATAGuestUserGuide.html">here</a> for instructions
on using FARMDATA.  After reading (or printing) the user's guide,
use the "back" button in your browser to return to FARMDATA.
-->
<p>
<center>
<img src="farmdata.png">
</center>

