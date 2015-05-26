<?php
/**
 * 4PSA VoipNow app: Facebook CallMe
 *  
 * This script handles the ajax requests and calls the appropriate functions in order to fulfill these requests
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/
require_once 'config/config.php';
require_once 'plib/lib.php';
session_start();
switch ($_POST["request_type"]) {
	case 'check_status':
		$result = getStatusResponse($_POST['url']);
		
		 echo $result;
		break;
	case 'initiate_call':
		$result = sendRequest($_POST['PhoneNumber']);

		echo $result;
		break;
	default;
		break;
}

?>