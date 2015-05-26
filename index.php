<?php
/**
 * 4PSA VoipNow App: Facebook CallMe
 *  
 * This script contains the display information and the connection with facebook
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/
/* starts session */
session_start();

/* requests facebook sdk definition */
require_once 'src/facebook.php';

/* remember where the user will be redirected once he logs in*/
$_SESSION['REDIRECT_URI'] = $config['REDIRECT_URI'];

/* new facebook object */
$facebook = new Facebook(array(
  'appId'  => $config['APP_ID'],
  'secret' => $config['APP_SECRET']
));

/* Get logged in user ID */
$user = $facebook->getUser();

//print_r('user='.$user);


/* 
 * 
 * We may or may not have this data based on whether the user is logged in.
 * If we have a $user id here, it means we know the user is logged into
 * Facebook, but we don't know if the access token is valid. An access
 * token is invalid if the user logged out of Facebook.
 *  
 */

if ($user) {
	try {
	    /* Proceed knowing you have a logged in user who's authenticated. */
	    $user_profile = $facebook->api('/me');
	    /* set user session with the user's profile */
	    $_SESSION["user"] = $user_profile;
	} catch (FacebookApiException $e) {
	    error_log($e);
	    $user = null;
	  }
}

/* Login or logout url will be needed depending on current user state. */
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
} else {
	$loginUrl = $facebook->getLoginUrl();
}
if (!$user) {
	
	/* user is logged out , or the access token is invalid 
	 *  javascript redirect for $loginUrl (!! modified in base_facebook.php )
	 */
	 echo '<script>top.location="'.$loginUrl.'";</script>';
	return;
} 

?>
<!doctype html>
<!-- html xmlns:fb="http://www.facebook.com/2008/fbml"  -->
<html>
<head>
<title>Facebook CallMe</title>
<script src="js/jquery-1.6.1.min.js"></script>
<script src="js/lib.js"> </script>
<link  rel="stylesheet" type="text/css" href="skin/index.css" />
</head>
<body>
	<div id = 'all' >
		<div id="header">
			Facebook CallMe
		</div>
		<div id = 'callstatus' ></div>
		<div id="description">
		Enter the phone number you want to call
		</div>
		<div id = 'callarea'>
			<div id='phoneform'>
				<input id="phoneinput" name="PhoneNumber" type="text"/>
				<input style="display: block;" id="callbutton" type="button" class="nicebutton" value="Call"/>
			</div>
			<div id = 'status'></div>
		</div>
	</div>

</body>
</html>
 
