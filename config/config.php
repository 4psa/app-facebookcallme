<?php
/**
 * 4PSA VoipNow App: Facebook CallMe
 *  
 * This script contains all the variables which have to be configured in order for the application to work properly.
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/


/**
 * The IP/Hostname of the VoipNow Professional server
 * @global string
 */
$config['VN_SERVER_IP'] = 'CHANGEME';

/**
 * the VoipNOW extension which Facebook users will call
 * @global string
 */
$config['VN_EXTENSION'] = 'CHANGEME';

/**
 * APP ID for 3-legged OAuth
 * Must be fetched from VoipNow interface
 * @global string
 */
$config['OAUTH_APP_ID'] = 'CHANGEME';

/**
 * APP Secret for 3-legged OAuth
 * Must be fetched from VoipNow interface
 * @global string
 */
$config['OAUTH_APP_SECRET'] = 'CHANGEME';

/**
 * Facebook App ID , which can be found on an application's settings page
 * @global string
 */
$config['APP_ID'] = 'CHANGEME';

/**
 * Facebook App Secret , which can be found on an application's settings page
 * @global string
 */
$config['APP_SECRET'] = 'CHANGEME';

/**
 * The Canvas Page of the Facebook App, as configured in the application's settings
 * @global string
 */
$config['REDIRECT_URI']="CHANGEME";
?>