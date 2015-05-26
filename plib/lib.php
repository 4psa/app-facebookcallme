<?php
/**
 * 4PSA VoipNow App: Facebook CallMe
 *  
 * This script contains the php functions and responds to the ajax request, made by the application at request.php
 *
 * @version 2.0.0
 * @license released under GNU General Public License
 * @copyright (c) 2012 4PSA. (www.4psa.com). All rights reserved.
 * @link http://wiki.4psa.com
*/
/* requires configuration file */
require_once 'cURLRequest.php';



/**
 * Generate a new token based on App ID and App secret
 *
 * @return string token
 * @return boolean FALSE when token could not be generated
 */
function generateToken() {
    global $config;

    $reqUrl = 'https://'.$config['VN_SERVER_IP'].'/oauth/token.php';

    $request = new cURLRequest();
    $request->setMethod(cURLRequest::METHOD_POST);

    $fields = array(
        'grant_type' => 'client_credentials',
        'redirect_uri' => $_SERVER['PHP_SELF'],
        'client_id' =>  urlencode($config['OAUTH_APP_ID']),
        'client_secret' => urlencode($config['OAUTH_APP_SECRET']),
        'state' => '0',

    );
    $request->setBody($fields);
    $response = $request->sendRequest($reqUrl);

    $respBody = $response->getBody();
    if ($response->getStatus() == Response::STATUS_OK && isset($respBody['access_token'])) {
        $_SESSION['FacebookCallMe']['token'] = 'Bearer '.$respBody['access_token'];
        return 'Bearer '.$respBody['access_token'];
    }
    return false;
}

/**
 * Get the token used for previous requests, or generate a new one if none exists
 *
 * @return string token
 */
function getToken() {
    if (isset($_SESSION['FacebookCallMe']['token']) && $_SESSION['FacebookCallMe']['token']) {
        $token = $_SESSION['FacebookCallMe']['token'];
    } else {
        /* generate token */
        $token = generateToken();
    }
    return $token;
}


/**
 * Make the UnifiedAPI request for calling the phone number. (PhoneCalls Create)
 *
 * @param string $phoneNumber The phone number.
 *
 * @return TRUE on sucess
 * @return FALSE on error
 *
 */
function sendRequest($phoneNumber){
	global $config;

	/* This is the URL accessed using the REST protocol */
	$reqUrl = 'https://'.$config['VN_SERVER_IP'].'/unifiedapi/phoneCalls/@me/simple';

    $token = getToken();
    if (!$token) {
        return false;
    }

    $headers = array(
        'Content-type' => 'application/json',
        'Authorization' => $token
    );

	$request = new cURLRequest();
	$request->setMethod(cURLRequest::METHOD_POST);
	$request->setHeaders($headers);


	$jsonRequest = array(
		'extension' => $config['VN_EXTENSION'],
		'phoneCallView' => array(array(
			'source' => array($config['VN_EXTENSION']),
			'destination' => $phoneNumber))
	);

	$request->setBody(json_encode($jsonRequest));
	$response = $request->sendRequest($reqUrl);

    if ($response->getStatus() == Response::STATUS_FORBIDDEN) {
        // try to regenerate token
        $headers['Authorization'] = generateToken();
        $request->setHeaders($headers);
        // retry request
        $response = $request->sendRequest($reqUrl);
    }
	return $response->getBody(true);
}

/**
 * This functions gets the call parameters from VoipNow using the UnifiedAPI.
 * @param string $url
 */
function getStatusResponse($url) {
	global $config;

    $token = getToken();
    if (!$token) {
        return false;
    }

    $headers = array(
        'Content-type' => 'application/json',
        'Authorization' => $token
    );

	$request = new cURLRequest();
	$request->setMethod(cURLRequest::METHOD_GET);
	$request->setHeaders($headers);
	$response = $request->sendRequest($url);

    if ($response->getStatus() == Response::STATUS_FORBIDDEN) {
        // try to regenerate token
        $headers['Authorization'] = generateToken();
        $request->setHeaders($headers);
        // retry request
        $response = $request->sendRequest($url);
    }
    return $response->getBody(true);
}